<?php

namespace BeeDelivery\RD;

use BeeDelivery\RD\Utils\Helpers;
use BeeDelivery\RD\Utils\MessageTypeRD;
use BeeDelivery\RD\Utils\StopSeqRD;
use BeeDelivery\RD\Utils\TrackingEnumRD;
use Google\Cloud\PubSub\MessageBuilder;

class Trackings
{
    use Helpers;

    protected $pubsub;

    protected $baseTracking;

    /*
     * Create a new Connection instance.
     *
     * @return void
     */
    public function __construct($informacoesPedido)
    {
        $this->pubsub = $this->pubSubGoogle();
        $this->baseTracking = $this->prepareBaseTracking($informacoesPedido);
    }

    private function tracking($messageData)
    {
        try {
            $topic = $this->pubsub->topic(config('rd.outbound_tender'));
            return $topic->publish((new MessageBuilder)->setData(json_encode($messageData))->build());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    private function prepareBaseTracking($informacoesPedido)
    {
        return [
            'Address1' => $informacoesPedido->Stop[1]->FacilityAddress->Address1 ?? null,
            'Address2' => $informacoesPedido->Stop[1]->FacilityAddress->Address2 ?? null,
            'CarrierId' => 'BEE',
            'City' => $informacoesPedido->Stop[1]->FacilityAddress->City ?? null,
            'CountryId' => $informacoesPedido->Stop[1]->FacilityAddress->Country ?? null,
            'Latitude' => $informacoesPedido->Stop[1]->Latitude ?? null,
            'Longitude' => $informacoesPedido->Stop[1]->Longitude ?? null,
            'OrgId' => 'RD-RaiaDrogasil-SA',
            'PostalCode' => $informacoesPedido->Stop[1]->FacilityAddress->PostalCode,
            'ReceivedTimeStamp' => now('UTC')->toDateTimeLocalString(),
            'ReceivedTimeZone' => 'Brazil/East',
            'ShipmentId' => $informacoesPedido->ShipmentId,
            'SourceType' => 'API',
            'StateId' => $informacoesPedido->Stop[1]->FacilityAddress->State ?? null,
            'TimeZone' => 'Brazil/East',
            'TrackingEventTimeStamp' => now('UTC')->toDateTimeLocalString(),
            'TrackingReference' => $informacoesPedido->ShipmentId,
            'TrackingType' => 'Shipment',
            'TransportationOrderId' => $informacoesPedido->ShipmentId,
            'Extended' => [
                'Pedido' => $informacoesPedido->ShipmentId,
            ]
        ];
    }

    public function integrated($deliveryManTracking)
    {
        $messageData = [
            'MessageComments' => $deliveryManTracking,
            'MessageName' => 'Integrado',
            'MessageType' => MessageTypeRD::INTEGRATED,
            'StopSeq' => StopSeqRD::INTEGRATED,
            'TrackingReasonCodeId' => TrackingEnumRD::INTEGRATED,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function quotation($deliveryPrice)
    {
        $messageData = [
            'MessageComments' => $deliveryPrice,
            'MessageName' => 'Preço da entrega',
            'MessageType' => MessageTypeRD::QUOTATION,
            'StopSeq' => StopSeqRD::QUOTATION,
            'TrackingReasonCodeId' => TrackingEnumRD::QUOTATION,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function onPickupRoute()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Em rota de coleta',
            'MessageType' => MessageTypeRD::ON_PICKUP_ROUTE,
            'StopSeq' => StopSeqRD::ON_PICKUP_ROUTE,
            'TrackingReasonCodeId' => TrackingEnumRD::ON_PICKUP_ROUTE,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function arrivalAtPickup()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no ponto de coleta',
            'MessageType' => MessageTypeRD::ARRIVAL_AT_PICKUP,
            'StopSeq' => StopSeqRD::ARRIVAL_AT_PICKUP,
            'TrackingReasonCodeId' => TrackingEnumRD::ARRIVAL_AT_PICKUP,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function dispatched()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Despachado',
            'MessageType' => MessageTypeRD::DISPATCHED,
            'StopSeq' => StopSeqRD::DISPATCHED,
            'TrackingReasonCodeId' => TrackingEnumRD::DISPATCHED,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function arrivalAtDelivery()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no destino do cliente',
            'MessageType' => MessageTypeRD::ARRIVAL_AT_DELIVERY,
            'StopSeq' => StopSeqRD::ARRIVAL_AT_DELIVERY,
            'TrackingReasonCodeId' => TrackingEnumRD::ARRIVAL_AT_DELIVERY,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function successulDelivery()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Entrega realizada com sucesso',
            'MessageType' => MessageTypeRD::SUCCESSFUL_DELIVERY,
            'StopSeq' => StopSeqRD::SUCCESSFUL_DELIVERY,
            'TrackingReasonCodeId' => TrackingEnumRD::SUCCESSFUL_DELIVERY,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function canceledDelivery($reasson)
    {
        $messageData = [
            'MessageComments' => $reasson,
            'MessageName' => 'Entrega cancelada - ' . $reasson,
            'MessageType' => MessageTypeRD::CANCELED_DELIVERY,
            'StopSeq' => StopSeqRD::CANCELED_DELIVERY,
            'TrackingReasonCodeId' => TrackingEnumRD::CANCELED_DELIVERY,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function returned($reasson)
    {
        $messageData = [
            'MessageComments' => $reasson,
            'MessageName' => 'Pedido devolvido em loja - ' . $reasson,
            'MessageType' => MessageTypeRD::RETURNED,
            'StopSeq' => StopSeqRD::RETURNED,
            'TrackingReasonCodeId' => TrackingEnumRD::RETURNED,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }
}
