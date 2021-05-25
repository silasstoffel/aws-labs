<?php

require '../../vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

// https://docs.aws.amazon.com/pt_br/sdk-for-php/v3/developer-guide/sqs-examples-using-queues.html

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
