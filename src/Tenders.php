<?php

namespace BeeDelivery\RaiaDrograsil;

use BeeDelivery\RaiaDrograsil\Utils\Helpers;
use BeeDelivery\RaiaDrograsil\Utils\TenderEnumRD;
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
            $topic = $this->pubsub->topic(config('raiadrogasil.tender_response'));
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
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
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

    public function declinedForaDeAbrangencia()
    {
        $tender = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => TenderEnumRD::FORA_DE_ABRANGENCIA,
                'ReasonMessage' => 'Fora de abrangência',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedFilialNaoCadastrada()
    {
        $tender = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => TenderEnumRD::FILIAL_NAO_CADASTRADA,
                'ReasonMessage' => 'Filial não cadastrada',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedSemEntregadorNaRegiao()
    {
        $tender = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => TenderEnumRD::SEM_ENTREGADOR_NA_REGIAO,
                'ReasonMessage' => 'Sem entregador na região',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedAreaDeRisco()
    {
        $tender = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => TenderEnumRD::AREA_DE_RISCO,
                'ReasonMessage' => 'Área de risco',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }

    public function declinedFalhaAoGeolocalizarDestino()
    {
        $tender = [
            'Metadata' => [
                'RouterMessageType' => 'CARRIER_TENDER_RESPONSE',
                'ActionType' => 'TENDER_DECLINE',
                'PartnerId' => 'raiassf11o:RD-RaiaDrogasil-SA',
                'SenderRouterOrgId' => 'mxcassf11o:' . $this->data->CarrierId,
                'PartnerAliasId' => $this->data->CarrierId,
            ],
            'TenderResponseDTO' => [
                'ShipmentId' => $this->data->ShipmentId,
                'ShipperId' => $this->data->ShipperId,
                'CarrierId' => $this->data->CarrierId,
                'ReasonCode' => TenderEnumRD::FALHA_AO_GEOLOCALIZAR_DESTINO,
                'ReasonMessage' => 'Falha ao geolocalizar destino',
                'TenderResponseStatus' => 'DECLINED',
            ],
        ];

        return $this->tender($tender);
    }
}
