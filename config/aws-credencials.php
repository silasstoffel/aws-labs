<?php

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

return [
    'key'    => $_ENV['AWS_SQS_KEY'],
    'secret' => $_ENV['AWS_SQS_SECRET'],
];
