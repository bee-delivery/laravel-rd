<?php

namespace BeeDelivery\RD;

use BeeDelivery\RD\Utils\Helpers;
use BeeDelivery\RD\Utils\MessageTypeRD;
use BeeDelivery\RD\Utils\StopSeqRD;
use BeeDelivery\RD\Utils\TrackingEnumRD;
use Google\Cloud\PubSub\MessageBuilder;
use Illuminate\Support\Facades\Log;

class Trackings
{
    use Helpers, MessageTypeRD, StopSeqRD, TrackingEnumRD;

    protected $pubsub;

    protected $baseTracking;

    /*
     * Create a new Connection instance.
     *
     * @return void
     */
    public function __construct($data, bool $setAddicionalMinute = false, $removeAddicionalMinute = false)
    {
        $this->pubsub = $this->pubSubGoogle();
        $this->baseTracking = $this->prepareBaseTracking($data, $setAddicionalMinute, $removeAddicionalMinute);
    }

    private function tracking($messageData, $retries = 3)
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $retries) {
            try {
                $topic = $this->pubsub->topic(config('rd.inbound_tracking_response'));
                return $topic->publish((new MessageBuilder)->setData(json_encode($messageData))->build());
            } catch (\Exception $exception) {
                $lastException = $exception;
                $attempt++;

                // Error log
                Log::error('Error publishing tracking', [
                    'shipment_id' => $messageData['ShipmentId'] ?? 'N/A',
                    'message_type' => $messageData['MessageType'] ?? 'N/A',
                    'error' => $exception->getMessage(),
                    'attempt' => $attempt,
                    'timestamp' => now('UTC')->toDateTimeLocalString()
                ]);

                if ($attempt < $retries) {
                    // Exponential backoff between attempts
                    sleep(pow(2, $attempt));
                }
            }
        }

        Log::critical('Critical failure publishing tracking after all attempts', [
            'shipment_id' => $messageData['ShipmentId'] ?? 'N/A',
            'message_type' => $messageData['MessageType'] ?? 'N/A',
            'error' => $lastException ? $lastException->getMessage() : 'Unknown error',
            'timestamp' => now('UTC')->toDateTimeLocalString()
        ]);

        throw new \Exception(
            "Failed to publish tracking after {$retries} attempts. Last error: " .
                ($lastException ? $lastException->getMessage() : 'Unknown error')
        );
    }

    private function prepareBaseTracking($data, bool $setAddicionalMinute = false, bool $removeAddicionalMinute = false)
    {
        $timestamp = $setAddicionalMinute ? now('UTC')->addMinute()->format('Y-m-d\TH:i:00') : ($removeAddicionalMinute ? now('UTC')->addMinutes(-1)->format('Y-m-d\TH:i:00') : now('UTC')->toDateTimeLocalString());
        $deliveryAddress = $this->getDeliveryAddress($data);
        return [
            'Address1' => $deliveryAddress->FacilityAddress->Address1 ?? null,
            'Address2' => $deliveryAddress->FacilityAddress->Address2 ?? null,
            'CarrierId' => 'BEE',
            'City' => $deliveryAddress->FacilityAddress->City ?? null,
            'CountryId' => $deliveryAddress->FacilityAddress->Country ?? null,
            'Latitude' => $deliveryAddress->Latitude ?? null,
            'Longitude' => $deliveryAddress->Longitude ?? null,
            'OrgId' => 'RD-RaiaDrogasil-SA',
            'PostalCode' => $deliveryAddress->FacilityAddress->PostalCode ?? null,
            'ShipmentId' => $data->ShipmentId,
            'SourceType' => 'API',
            'StateId' => $deliveryAddress->FacilityAddress->State ?? null,
            'TimeZone' => 'Brazil/East',
            'TrackingEventTimeStamp' => $timestamp,
            'TrackingReference' => $data->ShipmentId,
            'TrackingType' => 'Shipment',
            'TransportationOrderId' => $data->ShipmentId,
            'Extended' => [
                'Pedido' => $data->ShipmentId,
            ]
        ];
    }

    public function integrated($deliveryManTracking)
    {
        $messageData = [
            'MessageComments' => $deliveryManTracking,
            'MessageName' => 'Integrado',
            'MessageType' => $this::MT_INTEGRATED,
            'StopSeq' => $this::STQ_INTEGRATED,
            'TrackingReasonCodeId' => $this::TRK_INTEGRATED,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function quotation($deliveryPrice)
    {
        $messageData = [
            'MessageComments' => $deliveryPrice,
            'MessageName' => 'Preço da entrega',
            'MessageType' => $this::MT_QUOTATION,
            'StopSeq' => $this::STQ_QUOTATION,
            'TrackingReasonCodeId' => $this::TRK_QUOTATION,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function onPickupRoute($expectedTime)
    {
        $messageData = [
            'MessageComments' => $expectedTime,
            'MessageName' => 'Em rota de coleta',
            'MessageType' => $this::MT_ON_PICKUP_ROUTE,
            'StopSeq' => $this::STQ_ON_PICKUP_ROUTE,
            'TrackingReasonCodeId' => $this::TRK_ON_PICKUP_ROUTE,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function arrivalAtPickup()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no ponto de coleta',
            'MessageType' => $this::MT_ARRIVAL_AT_PICKUP,
            'StopSeq' => $this::STQ_ARRIVAL_AT_PICKUP,
            'TrackingReasonCodeId' => $this::TRK_ARRIVAL_AT_PICKUP,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function dispatched($expectedTime)
    {
        $messageData = [
            'MessageComments' => $expectedTime,
            'MessageName' => 'Despachado',
            'MessageType' => $this::MT_DISPATCHED,
            'StopSeq' => $this::STQ_DISPATCHED,
            'TrackingReasonCodeId' => $this::TRK_DISPATCHED,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function arrivalAtDelivery(?string $messageComments = null)
    {
        $messageData = [
            'MessageComments' => $messageComments === null ? now('UTC')->toDateTimeLocalString() : substr($messageComments, 0, 50),
            'MessageName' => 'Chegada no destino do cliente',
            'MessageType' => $this::MT_ARRIVAL_AT_DELIVERY,
            'StopSeq' => $this::STQ_ARRIVAL_AT_DELIVERY,
            'TrackingReasonCodeId' => $this::TRK_ARRIVAL_AT_DELIVERY,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function arrivalAtDeliveryWithDelayOnTracking(?string $messageComments = null)
    {
        $messageData = [
            'MessageComments' => $messageComments === null ? now('UTC')->addMinutes(-1)->toDateTimeLocalString() : substr($messageComments, 0, 50),
            'MessageName' => 'Chegada no destino do cliente',
            'MessageType' => $this::MT_ARRIVAL_AT_DELIVERY,
            'StopSeq' => $this::STQ_ARRIVAL_AT_DELIVERY,
            'TrackingReasonCodeId' => $this::TRK_ARRIVAL_AT_DELIVERY,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function successulDelivery(?string $messageComments = null)
    {
        $messageData = [
            'MessageComments' => $messageComments === null ? now('UTC')->addMinute()->format('Y-m-d\TH:i:00') : substr($messageComments, 0, 50),
            'MessageName' => 'Entrega realizada com sucesso',
            'MessageType' => $this::MT_SUCCESSFUL_DELIVERY,
            'StopSeq' => $this::STQ_SUCCESSFUL_DELIVERY,
            'TrackingReasonCodeId' => $this::TRK_SUCCESSFUL_DELIVERY,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function canceledDelivery($reasson)
    {
        $messageData = [
            'MessageComments' => substr($reasson, 0, 50),
            'MessageName' => substr('Entrega cancelada - ' . $reasson, 0, 50),
            'MessageType' => $this::MT_CANCELED_DELIVERY,
            'StopSeq' => $this::STQ_CANCELED_DELIVERY,
            'TrackingReasonCodeId' => $this::TRK_CANCELED_DELIVERY,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function returned($reasson)
    {
        $messageData = [
            'MessageComments' => substr($reasson, 0, 50),
            'MessageName' => substr('Pedido devolvido em loja - ' . $reasson, 0, 50),
            'MessageType' => $this::MT_RETURNED,
            'StopSeq' => $this::STQ_RETURNED,
            'TrackingReasonCodeId' => $this::TRK_RETURNED,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function reject($reasson)
    {
        $messageData = [
            'MessageComments' => substr($reasson, 0, 50),
            'MessageName' => substr('Pedido recusado - ' . $reasson, 0, 50),
            'MessageType' => $this::MT_REJECTED_BY_CARRIER,
            'StopSeq' => $this::STQ_REJECTED_BY_CARRIER,
            'TrackingReasonCodeId' => $this::TRK_REJECTED_BY_CARRIER,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function deliverymanAbandonedTheOrder()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Entregador desistiu do pedido',
            'MessageType' => $this::MT_DELIVERYMAN_ABANDONED_THE_ORDER,
            'StopSeq' => $this::STQ_DELIVERYMAN_ABANDONED_THE_ORDER,
            'TrackingReasonCodeId' => $this::TRK_DELIVERYMAN_ABANDONED_THE_ORDER,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function absentClient()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Cliente ausente',
            'MessageType' => $this::MT_CLIENT_ABSENT,
            'StopSeq' => $this::STQ_CLIENT_ABSENT,
            'TrackingReasonCodeId' => $this::TRK_CLIENT_ABSENT,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function addressNotFound()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Endereço não encontrado',
            'MessageType' => $this::MT_ADDRESS_NOT_FOUND,
            'StopSeq' => $this::STQ_ADDRESS_NOT_FOUND,
            'TrackingReasonCodeId' => $this::TRK_ADDRESS_NOT_FOUND,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function otherOccurrence($reasson)
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => substr($reasson, 0, 50),
            'MessageType' => $this::MT_OTHERS,
            'StopSeq' => $this::STQ_OTHERS,
            'TrackingReasonCodeId' => $this::TRK_OTHER_OCCURRENCE,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function orderRefusedByClient()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Pedido recusado pelo cliente',
            'MessageType' => $this::MT_ORDER_REFUSED_BY_CLIENT,
            'StopSeq' => $this::STQ_ORDER_REFUSED_BY_CLIENT,
            'TrackingReasonCodeId' => $this::TRK_ORDER_REFUSED_BY_CLIENT,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function orderNotCollected(string $reasson)
    {
        $messageData = [
            'MessageComments' => substr($reasson, 0, 50),
            'MessageName' => substr('Pedido não coletado - ' . $reasson, 0, 50),
            'MessageType' => $this::MT_NOT_COLLECTED,
            'StopSeq' => $this::STQ_NOT_COLLECTED,
            'TrackingReasonCodeId' => $this::TRK_NOT_COLLECTED,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function deliveryManNotFound()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Entregador não encontrado',
            'MessageType' => $this::MT_DELIVERER_NOT_FOUND,
            'StopSeq' => $this::STQ_DELIVERER_NOT_FOUND,
            'TrackingReasonCodeId' => $this::TRK_DELIVERER_NOT_FOUND,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function establishmentClosed()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Estabelecimento fechado',
            'MessageType' => $this::MT_ESTABLISHMENT_CLOSED,
            'StopSeq' => $this::STQ_ESTABLISHMENT_CLOSED,
            'TrackingReasonCodeId' => $this::TRK_ESTABLISHMENT_CLOSED,
        ];
        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    private function getDeliveryAddress($data)
    {
        if ($data->Stop[0]->StopSequence == 2) {
            return $data->Stop[0];
        }  
        if ($data->Stop[1]->StopSequence == 2) {
            return $data->Stop[1];
        }

        throw new \Exception('Address not found. Please check the shipment data.');
    }
}
