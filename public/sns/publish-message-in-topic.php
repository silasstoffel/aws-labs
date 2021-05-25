<?php

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

require '../../vendor/autoload.php';

$clientConfig = require '../../config/aws-sdk.php';

$topic = $_ENV['AWS_SNS_ARN_TOPIC_NEW_CUSTOMER'] ?? null;

if (is_null($topic)) {
    throw new InvalidArgumentException('Set value in AWS_SNS_ARN_TOPIC_NEW_CUSTOMER in .env file.');
}

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

$snsData = [
    'TopicArn' => $topic,
    'Message'  => json_encode($customer),
];

$response = [];

try {
    $snsClient = new SnsClient($clientConfig);
    $result = $snsClient->publish($snsData);
    var_dump($result);
} catch (AwsException $e) {
    http_response_code(500);
    echo json_encode([
        'message' => $e->getMessage(),
    ]);
}
