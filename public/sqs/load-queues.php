<?php

require '../../vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

$credentials = require '../../config/aws-credencials.php';

$clientConfig = [
    'region'      => 'sa-east-1',
    'version'     => 'latest',
    'credentials' => $credentials,
];

try {
    $client = new SqsClient($clientConfig);
    $result = $client->listQueues();
    foreach ($result->get('QueueUrls') as $queueUrl) {
        echo "$queueUrl<br/>";
    }
} catch (AwsException $e) {
    echo $e->getMessage();
}
