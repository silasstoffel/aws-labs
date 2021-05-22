<?php

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

return [
    'region'      => 'sa-east-1',
    'version'     => 'latest',
    'credentials' => [
        'key'    => $_ENV['AWS_SQS_KEY'],
        'secret' => $_ENV['AWS_SQS_SECRET'],
    ],
];
