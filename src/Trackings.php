<?php

namespace BeeDelivery\RD;

use BeeDelivery\RD\Utils\Helpers;
use BeeDelivery\RD\Utils\MessageTypeRD;
use BeeDelivery\RD\Utils\StopSeqRD;
use BeeDelivery\RD\Utils\TrackingEnumRD;
use Google\Cloud\PubSub\MessageBuilder;

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
    public function __construct($data)
    {
        $this->pubsub = $this->pubSubGoogle();
        $this->baseTracking = $this->prepareBaseTracking($data);
    }

    private function tracking($messageData)
    {
        try {
            $topic = $this->pubsub->topic(config('rd.inbound_tracking_response'));
            return $topic->publish((new MessageBuilder)->setData(json_encode($messageData))->build());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    private function prepareBaseTracking($data)
    {
        return [
            'Address1' => $data->Stop[1]->FacilityAddress->Address1 ?? null,
            'Address2' => $data->Stop[1]->FacilityAddress->Address2 ?? null,
            'CarrierId' => 'BEE',
            'City' => $data->Stop[1]->FacilityAddress->City ?? null,
            'CountryId' => $data->Stop[1]->FacilityAddress->Country ?? null,
            'Latitude' => $data->Stop[1]->Latitude ?? null,
            'Longitude' => $data->Stop[1]->Longitude ?? null,
            'OrgId' => 'RD-RaiaDrogasil-SA',
            'PostalCode' => $data->Stop[1]->FacilityAddress->PostalCode,
            'ReceivedTimeStamp' => now('UTC')->toDateTimeLocalString(),
            'ReceivedTimeZone' => 'Brazil/East',
            'ShipmentId' => $data->ShipmentId,
            'SourceType' => 'API',
            'StateId' => $data->Stop[1]->FacilityAddress->State ?? null,
            'TimeZone' => 'Brazil/East',
            'TrackingEventTimeStamp' => now('UTC')->toDateTimeLocalString(),
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

    public function arrivalAtDelivery()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no destino do cliente',
            'MessageType' => $this::MT_ARRIVAL_AT_DELIVERY,
            'StopSeq' => $this::STQ_ARRIVAL_AT_DELIVERY,
            'TrackingReasonCodeId' => $this::TRK_ARRIVAL_AT_DELIVERY,
        ];

        $data = array_merge($this->baseTracking, $messageData);
        return ['tracinkg' => $this->tracking($data), 'data' => $data];
    }

    public function successulDelivery()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
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
            'MessageComments' => $reasson,
            'MessageName' => 'Entrega cancelada - ' . $reasson,
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
            'MessageComments' => $reasson,
            'MessageName' => 'Pedido devolvido em loja - ' . $reasson,
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
            'MessageComments' => $reasson,
            'MessageName' => 'Pedido recusado - ' . $reasson,
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
}
