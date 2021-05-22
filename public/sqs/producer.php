<?php

require '../../vendor/autoload.php';

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

$clientConfig = require '../../config/aws-sdk.php';

$queueUrl = $_ENV['AWS_SQS_QUEUE_ADD_CUSTOMER'] ?? null;

if (is_null($queueUrl)) {
    throw new InvalidArgumentException('Set value in AWS_SQS_QUEUE_ADD_CUSTOMER in .env file.');
}

$attributes = [
    'Title'  => [
        'DataType'    => 'String',
        'StringValue' => 'Create Customer',
    ],
    'Author' => [
        'DataType'    => 'String',
        'StringValue' => 'My E-Comerce Labs',
    ],
];

$faker = Faker\Factory::create();
$faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
$faker->addProvider(new Faker\Provider\pt_BR\Address($faker));
$faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));

$person = new Faker\Generator();

$createdAt = new DateTime();

$customer = [
    'action'     => 'Customer.Create',
    'created_at' => $createdAt->format('c'),
    'payload'    => [
        'id'           => $faker->uuid,
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'email'        => $faker->email,
        'state'        => $faker->state,
        'city'         => $faker->city,
        'address'      => $faker->address,
        'country'      => $faker->country,
        'phone_number' => $faker->phoneNumber,
    ],
];

$message = [
    'DelaySeconds'       => 10,
    'QueueUrl'           => $queueUrl,
    'MessageAttributes' => $attributes, // optional
    'MessageBody'        => json_encode($customer),
];

try {
    $client = new SqsClient($clientConfig);
    $result = $client->sendMessage($message);

    echo 'Message ID: ' . $result->get('MessageId');
    echo '<hr/>';

    echo 'MD5OfMessageBody: ' . $result->get('MD5OfMessageBody');
    echo '<hr/>';

    $metadata = $result->get('@metadata');
    echo 'Metadata';

    echo '<pre>';
    var_dump($metadata);
    echo '</pre>';

} catch (AwsException $e) {
    echo $e->getMessage();
}
