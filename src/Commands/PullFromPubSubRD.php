<?php

namespace  Beedelivery\RD\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use BeeDelivery\RD\Utils\Helpers;

class PullFromPubSubRD extends Command
{
    use Helpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pulling:tms-rd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check delivery batches in RD via TMS';

    private $client;

    private $topicArnF3;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = $this->connectionSNS();
        $this->topicArnF3 = config('rd.aws_arn_sns');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('inicio...');
        // pull de pedidos no canal TMS
        $pubSub = $this->pubSubGoogle();

        // Faz a inscrição no canal TMS
        $subscription = $pubSub->subscription(config('rd.outbound_tender'));
        $beginHour = now();
        do {
            $messages = $subscription->pull();

            foreach ($messages as $key => $message) {
                try {
                    $count = $key + 1;
                    $this->info($count . ' de ' . count($messages));
                    // array para decorator com os parametros do pedido RD
                    $pedidoDecorator = json_decode($message->data());
                    if (isset($pedidoDecorator->TenderStatus) and $this->putInF3Queue($message->data())) {
                        $this->putOrderOnRedis($pedidoDecorator->ShipmentId, $pedidoDecorator);
                        $subscription->acknowledge($message);
                    } else {
                        $subscription->acknowledge($message);
                        Redis::hset(config('rd.redis_error_key_shipment'), now()->format('d/m/Y H:i:s'), json_encode(['data' => $pedidoDecorator]));
                        Redis::expire(config('rd.redis_error_key_shipment'), 432000); // 5 dias
                    }
                } catch (Exception $exception) {
                    Log::channel(config('rd.log_pull_tms'))->info(json_encode([
                        'msg' => 'Catch para o PullTmsRD',
                        'class' => 'PullFromPubSubRD',
                        'line' => $exception->getLine(),
                        'message' => $exception->getMessage(),
                        'exception' => json_encode($exception),
                    ]));
                    $this->info($exception->getMessage());
                }
            }
        } while (count($messages) > 0 and $beginHour->diffInSeconds(now()) < 50);

        $this->info('FIM');
    }

    private function putOrderOnRedis($orderId, $data)
    {
        Redis::hset(config('rd.redis_key_shipment') . $orderId, now()->format('d/m/Y H:i:s'), json_encode(['address' => $data->Stop[1]]));
        Redis::expire(config('rd.redis_key_shipment') . $orderId, 864000); // 10 dias
        Log::channel(config('rd.log_pull_tms'))->info(json_encode([
            'msg' => 'PEDIDO RECEBIDO: ' . $orderId,
            'hora' => now()->format('d/m/Y H:i:s'),
            'class' => 'PullFromPubSubRD',
        ]));
    }

    private function putInF3Queue($message)
    {
        try {
            $id = Str::random(100);
            $subject = 'Message to Fase3 model RD';
            $response = $this->client->publish([
                'TopicArn' => $this->topicArnF3,
                'MessageGroupId' => $id,
                'MessageDeduplicationId' => $id,
                'Message' => $message,
                'Subject' => $subject,
            ]);
            $meta = $response->get('@metadata');
            if ($meta['statusCode'] === 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $th) {
            throw $th;
        }
    }
}
