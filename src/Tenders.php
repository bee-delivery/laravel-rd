<?php

namespace BeeDelivery\RD;

use BeeDelivery\RD\Utils\Helpers;
use BeeDelivery\RD\Utils\TenderEnumRD;
use Google\Cloud\PubSub\MessageBuilder;

class Tenders
{
    use Helpers, TenderEnumRD;

    protected $pubsub;

    protected $data;

    /*
     * Create a new Connection instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->pubsub = $this->pubSubGoogle();
        $this->data = $data;
    }

    private function tender($messageData)
    {
        try {
            $topic = $this->pubsub->topic(config('rd.inbound_tender_response'));
            return $topic->publish((new MessageBuilder)->setData(json_encode($messageData))->build());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function accepted()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_ACCEPT',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => null,
                'ReasonMessage' => null,
                'TenderResponseStatus' => 'ACCEPTED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedOutOfCoverage()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_OUT_OF_COVERAGE,
                'ReasonMessage' => 'Fora de abrangência',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedUnregistredBranch()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_UNREGISTERED_BRANCH,
                'ReasonMessage' => 'Filial não cadastrada',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedNoDelivererInRegion()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_NO_DELIVERER_IN_REGION,
                'ReasonMessage' => 'Sem entregador na região',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedRiskArea()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_RISK_AREA,
                'ReasonMessage' => 'Área de risco',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedFailedToGeolocateDestination()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_FAILED_TO_GEOLOCATE_DESTINATION,
                'ReasonMessage' => 'Falha ao geolocalizar destino',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }

    public function declinedOutOfServiceWindow()
    {
        $data = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => config('rd.partner_id'),
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => $this::TND_OUT_OF_SERVICE_WINDOW,
                'ReasonMessage' => 'Fora da janela de atendimento',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return ['tender' => $this->tender($data), 'data' => $data];
    }
}
