<?php

namespace BeeDelivery\RaiaDrograsil\Utils;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Facades\Validator;

trait Helpers
{
    function connectionSNS()
    {
        return new \Aws\Sns\SnsClient([
            'version' => 'latest',
            'region' => config('raiadrograsil.aws_region'),
            'credentials' => [
                'key' => config('raiadrograsil.aws_access_key_id'),
                'secret' => config('raiadrograsil.aws_secret_access_key')
            ]
        ]);
    }

    function pubSubGoogle() {
        return new PubSubClient([
            'keyFile' => json_decode(file_get_contents(config('raiadrogasil.config_file')), true),
        ]);
    }
}
