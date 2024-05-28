<?php

namespace App\Console\Commands;

use BeeDelivery\RaiaDrograsil\Utils\Helpers;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class PullFromPubSubRD extends Command
{
    use Helpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pulling:tms-raia-drogasil';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar lotes de entregas na Raia Drogasil via TMS';

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
        $this->topicArnF3 = config('raiadrogasil.aws_arn_sns');
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
        $subscription = $pubSub->subscription(config('raiadrogasil.outbound_tender'));
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
                        Redis::hset('pedido-recebido-com-erro-rd', now()->format('d/m/Y H:i:s'), json_encode(['data' => $pedidoDecorator]));
                    }
                } catch (Exception $exception) {
                    Log::channel('rd_tms_responses')->info(json_encode([
                        'msg' => 'Catch para o PullTmsRaiaDrogasil',
                        'class' => 'PullTmsRaiaDrogasil',
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
        Redis::hset('pedido-recebido-rd-' . $orderId, now()->format('d/m/Y H:i:s'), json_encode(['address' => $data->Stop[1]]));
        Redis::expire('pedido-recebido-rd-' . $orderId, 864000); // 10 dias
        Log::channel('pull_tms_send_aws')->info(json_encode([
            'msg' => 'PEDIDO RECEBIDO: ' . $orderId,
            'hora' => now()->format('d/m/Y H:i:s'),
            'class' => 'PullTmsRaiaDrogasil',
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
