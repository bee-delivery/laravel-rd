<?php

namespace BeeDelivery\RD\Utils;

use Google\Cloud\PubSub\PubSubClient;

trait Helpers
{
    public function connectionSNS()
    {
        return new \Aws\Sns\SnsClient([
            'version' => 'latest',
            'region' => config('rd.aws_region'),
            'credentials' => [
                'key' => config('rd.aws_access_key_id'),
                'secret' => config('rd.aws_secret_access_key')
            ]
        ]);
    }

    public function pubSubGoogle()
    {
        return new PubSubClient([
            'keyFile' => json_decode(file_get_contents(config('rd.config_file')), true),
        ]);
    }
}
