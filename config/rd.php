<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'aws_arn_sns' => env('RD_AWS_ARN_SNS'),
    'aws_region' => env('RD_AWS_REGION'),
    'aws_access_key_id' => env('RD_AWS_ACCESS_KEY_ID'),
    'aws_secret_access_key' => env('RD_AWS_SECRET_ACCESS_KEY'),
    'aws_sqs_url' => env('RD_AWS_SQS_URL'),
    'config_file' => env('RD_CONFIG_FILE'),
    'outbound_tender' => env('RD_OUTBOUND_TENDER'),
    'inbound_tender' => env('RD_INBOUND_TENDER'),
    'tender_response' => env('RD_TENDER_RESPONSE'),
    'partner_id' => env('RD_PARTNER_ID'),
    'redis_key_shipment' => env('RD_REDIS_KEY_SHIPMENT'),
    'redis_error_key_shipment' => env('RD_REDIS_ERROR_KEY_SHIPMENT'),
    'log_pull_tms' => env('RD_LOG_PULL_TMS'),
];