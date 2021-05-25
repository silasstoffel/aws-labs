<?php

require '../../vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

// https://docs.aws.amazon.com/pt_br/sdk-for-php/v3/developer-guide/sqs-examples-send-receive-messages.html

$clientConfig = require '../../config/aws-sdk.php';

$queueUrl = $_ENV['AWS_SQS_QUEUE_ADD_CUSTOMER'] ?? null;

if (is_null($queueUrl)) {
    throw new InvalidArgumentException('Set value in AWS_SQS_QUEUE_ADD_CUSTOMER in .env file.');
}

header('Content-Type: application/json');

$configReceive = [
    'AttributesNames'     => ['Title', 'Author'],
    'MaxNumberOfMessages' => 10,
    'AttributesNames'     => ['All'],
    'QueueUrl'            => $queueUrl,
    'WaitTimeSeconds'     => 0,
];
$response = [];
try {
    $client   = new SqsClient($clientConfig);
    $result   = $client->receiveMessage($configReceive);
    $messages = $result->get('Messages');
    if (!empty($messages)) {
        $results    = [];
        $deleteInfo = [
            'QueueUrl'      => $queueUrl,
            'ReceiptHandle' => null,
        ];
        foreach ($messages as $message) {
            $results[] = json_decode($message['Body']);
            $deleteInfo['ReceiptHandle'] = $message['ReceiptHandle'];
            // Delete Sync
            $result = $client->deleteMessage($deleteInfo);
        }
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(200);
        echo json_encode([
            'message' => 'No messages in queue.',
        ]);
    }
} catch (AwsException $e) {
    http_response_code(500);
    echo json_encode([
        'message' => $e->getMessage(),
    ]);
}
