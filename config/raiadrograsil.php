<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'aws_arn_sns' => env('RAIADROGASIL_AWS_ARN_SNS'),
    'aws_region' => env('RAIADROGASIL_AWS_REGION'),
    'aws_access_key_id' => env('RAIADROGASIL_AWS_ACCESS_KEY_ID'),
    'aws_secret_access_key' => env('RAIADROGASIL_AWS_SECRET_ACCESS_KEY'),
    'aws_sqs_url' => env('RAIADROGASIL_AWS_SQS_URL'),
    'config_file' => env('RAIADROGASIL_CONFIG_FILE'),
    'outbound_tender' => env('RAIADROGASIL_OUTBOUND_TENDER'),
    'inbound_tender' => env('RAIADROGASIL_INBOUND_TENDER'),
    'tender_response' => env('RAIADROGASIL_TENDER_RESPONSE'),
];