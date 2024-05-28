<?php

namespace BeeDelivery\RaiaDrograsil;

use BeeDelivery\RaiaDrograsil\Utils\Helpers;
use BeeDelivery\RaiaDrograsil\Utils\MessageTypeRD;
use BeeDelivery\RaiaDrograsil\Utils\StopSeqRD;
use BeeDelivery\RaiaDrograsil\Utils\TrackingEnumRD;
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
            $topic = $this->pubsub->topic(config('raiadrogasil.outbound_tender'));
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

    public function integrado($deliveryManTracking)
    {
        $messageData = [
            'MessageComments' => $deliveryManTracking,
            'MessageName' => 'Integrado',
            'MessageType' => MessageTypeRD::INTEGRADO,
            'StopSeq' => StopSeqRD::INTEGRADO,
            'TrackingReasonCodeId' => TrackingEnumRD::INTEGRADO,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function cotacao($deliveryPrice)
    {
        $messageData = [
            'MessageComments' => $deliveryPrice,
            'MessageName' => 'Preço da entrega',
            'MessageType' => MessageTypeRD::COTACAO,
            'StopSeq' => StopSeqRD::COTACAO,
            'TrackingReasonCodeId' => TrackingEnumRD::COTACAO,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function emRotaDeColeta()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Em rota de coleta',
            'MessageType' => MessageTypeRD::EM_ROTA_DE_COLETA,
            'StopSeq' => StopSeqRD::EM_ROTA_DE_COLETA,
            'TrackingReasonCodeId' => TrackingEnumRD::EM_ROTA_DE_COLETA,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function chegadaNoPontoDeColeta()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no ponto de coleta',
            'MessageType' => MessageTypeRD::CHEGADA_NA_COLETA,
            'StopSeq' => StopSeqRD::CHEGADA_NA_COLETA,
            'TrackingReasonCodeId' => TrackingEnumRD::CHEGADA_NA_COLETA,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function despachado()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Despachado',
            'MessageType' => MessageTypeRD::DESPACHADO,
            'StopSeq' => StopSeqRD::DESPACHADO,
            'TrackingReasonCodeId' => TrackingEnumRD::DESPACHADO,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function chegadaNaEntrega()
    {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Chegada no destino do cliente',
            'MessageType' => MessageTypeRD::CHEGADA_NA_ENTREGA,
            'StopSeq' => StopSeqRD::CHEGADA_NA_ENTREGA,
            'TrackingReasonCodeId' => TrackingEnumRD::CHEGADA_NA_ENTREGA,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function entregaRealizada() {
        $messageData = [
            'MessageComments' => now('UTC')->toDateTimeLocalString(),
            'MessageName' => 'Entrega realizada com sucesso',
            'MessageType' => MessageTypeRD::ENTREGA_REALIZADA_COM_SUCESSO,
            'StopSeq' => StopSeqRD::ENTREGA_REALIZADA_COM_SUCESSO,
            'TrackingReasonCodeId' => TrackingEnumRD::ENTREGA_REALIZADA_COM_SUCESSO,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function entregaCancelada($motivo)
    {
        $messageData = [
            'MessageComments' => $motivo,
            'MessageName' => 'Entrega cancelada - '. $motivo,
            'MessageType' => MessageTypeRD::ENTREGA_CANCELADA,
            'StopSeq' => StopSeqRD::ENTREGA_CANCELADA,
            'TrackingReasonCodeId' => TrackingEnumRD::ENTREGA_CANCELADA,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }

    public function devolvido($motivo) {
        $messageData = [
            'MessageComments' => $motivo,
            'MessageName' => 'Pedido devolvido em loja - ' . $motivo,
            'MessageType' => MessageTypeRD::DEVOLVIDO,
            'StopSeq' => StopSeqRD::DEVOLVIDO,
            'TrackingReasonCodeId' => TrackingEnumRD::DEVOLVIDO,
        ];

        return $this->tracking(array_merge($this->baseTracking, $messageData));
    }
}
