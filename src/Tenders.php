<?php

namespace BeeDelivery\RD;

use BeeDelivery\RD\Utils\Helpers;
use BeeDelivery\RD\Utils\TenderEnumRD;
use Google\Cloud\PubSub\MessageBuilder;

class Tenders
{
    use Helpers;

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
            $topic = $this->pubsub->topic(config('rd.tender_response'));
            return $topic->publish((new MessageBuilder)->setData(json_encode($messageData))->build());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function accepted()
    {
        $tender = [
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

        return $this->tender($tender);
    }

    public function declinedOutOfCoverage()
    {
        $tender = [
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
                'ReasonCode' => TenderEnumRD::OUT_OF_COVERAGE,
                'ReasonMessage' => 'Fora de abrangência',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedUnregistredBranch()
    {
        $tender = [
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
                'ReasonCode' => TenderEnumRD::UNREGISTERED_BRANCH,
                'ReasonMessage' => 'Filial não cadastrada',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedNoDelivererInRegion()
    {
        $tender = [
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
                'ReasonCode' => TenderEnumRD::NO_DELIVERER_IN_REGION,
                'ReasonMessage' => 'Sem entregador na região',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedRiskArea()
    {
        $tender = [
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
                'ReasonCode' => TenderEnumRD::RISK_AREA,
                'ReasonMessage' => 'Área de risco',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedFailedToGeolocateDestination()
    {
        $tender = [
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
                'ReasonCode' => TenderEnumRD::FAILED_TO_GEOLOCATE_DESTINATION,
                'ReasonMessage' => 'Falha ao geolocalizar destino',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }
}