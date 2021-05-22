<?php

require '../../vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

$clientConfig = require '../../config/aws-sdk.php';

try {
    $client = new SqsClient($clientConfig);
    $result = $client->listQueues();
    foreach ($result->get('QueueUrls') as $queueUrl) {
        echo "$queueUrl<br/>";
    }
} catch (AwsException $e) {
    echo $e->getMessage();
}
