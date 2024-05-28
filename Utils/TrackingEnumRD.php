<?php

namespace BeeDelivery\RaiaDrograsil\Utils;

class TrackingEnumRD
{
    public const INTEGRADO = '1';
    public const CONFERENCIA_FISICA = '2';
    public const ARMAZENADO = '3';
    public const DESPACHADO = '4';
    public const LOCALIDADE_NAO_ATENDIDA = '5';
    public const CLIENTE_AUSENTE = '6';
    public const PEDIDO_DANIFICADO = '7';
    public const ENTREGA_CANCELADA = '8';
    public const PEDIDO_EXTRAVIADO = '9';
    public const ENTREGA_REALIZADA_COM_SUCESSO = '10';
    public const COLETA_REVERSA_REALIZADA = '11';
    public const PEDIDO_RECUSADO_CLIENTE = '12';
    public const ESTABELECIMENTO_FECHADO = '13';
    public const ENDERECO_NAO_LOCALIZADO = '14';
    public const CANCELADO_PELO_CLIENTE = '15';
    public const CLIENTE_INEXISTENTE = '16';
    public const OCORRENCIA_OUTROS = '17';
    public const DEVOLVIDO = '19';
    public const NUMERO_NAO_LOCALIZADO = '20';
    public const EM_ROTA_DE_COLETA = '21';
    public const COLETA_DE_RECEITA_REALIZADA = '22';
    public const EM_ROTA_DE_ENTREGA_RECEITA = '23';
    public const ENTREGA_RECEITA = '24';
    public const COLETA_PEDIDOS = '25';
    public const NAO_COLETADO = '26';
    public const TRANSBORDO = '27';
    public const CHEGADA_NA_COLETA = '28';
    public const CHEGADA_NA_ENTREGA = '29';
    public const AGUARDANDO_RETIRADA_DO_CLIENTE = '30';
    public const ACAREAMENTO_EXTRAVIO = '31';
    public const EM_PROCESSO_DE_DEVOLUCAO = '32';
    public const DEVOLVIDO_POR_EXTRAVIO = '33';
    public const ACAREAMENTO_FINALIZADO_EXTRAVIO = '34';
    public const RETENCAO_FISCAL = '35';
    public const REJEITADO_PELA_TRANSPORTADORA = '36';
    public const POSICAO_ATUALIZADA_GPS = '37';
    public const ENTREGADOR_NAO_LOCALIZADO = '38';
    public const PROCURANDO_ENTREGADOR = '39';
    public const COTACAO = '40';
}

class StopSeqRD
{
    public const INTEGRADO = '1';
    public const CONFERENCIA_FISICA = '1';
    public const ARMAZENADO = '1';
    public const DESPACHADO = '1';
    public const LOCALIDADE_NAO_ATENDIDA = '1';
    public const CLIENTE_AUSENTE = '1';
    public const PEDIDO_DANIFICADO = '1';
    public const ENTREGA_CANCELADA = '1';
    public const PEDIDO_EXTRAVIADO = '1';
    public const ENTREGA_REALIZADA_COM_SUCESSO = '2';
    public const COLETA_REALIZADA = '1';
    public const PEDIDO_RECUSADO_CLIENTE = '1';
    public const ESTABELECIMENTO_FECHADO = '1';
    public const ENDERECO_NAO_LOCALIZADO = '1';
    public const CANCELADO_PELO_CLIENTE = '1';
    public const CLIENTE_INEXISTENTE = '1';
    public const OUTROS = '1';
    public const DEVOLVIDO = '1';
    public const NUMERO_NAO_LOCALIZADO = '1';
    public const INICIO_DE_DESLOCAMENTO_AO_CLIENTE = '1';
    public const COLETA_DE_RECEITA_REALIZADA = '1';
    public const EM_ROTA_DE_COLETA = '1';
    public const ENTREGA_DE_RECEITA_NA_FARMACIA = '1';
    public const COLETA_PEDIDOS = '1';
    public const NAO_COLETADO = '1';
    public const TRANSBORDO = '1';
    public const CHEGADA_NA_COLETA = '1';
    public const CHEGADA_NA_ENTREGA = '2';
    public const AGUARDANDO_RETIRADA_DO_CLIENTE = '1';
    public const ACAREAMENTO_EXTRAVIO = '1';
    public const EM_PROCESSO_DE_DEVOLUCAO = '1';
    public const DEVOLVIDO_POR_EXTRAVIO = '1';
    public const ACAREAMENTO_FINALIZADO_EXTRAVIO = '1';
    public const RETENCAO_FISCAL = '1';
    public const REJEITADO_PELA_TRANSPORTADORA = '1';
    public const POSICAO_ATUALIZADA_GPS = 'null';
    public const ENTREGADOR_NAO_LOCALIZADO = '1';
    public const PROCURANDO_ENTREGADOR = '1';
    public const COTACAO = '1';
}

class MessageTypeRD
{
    public const INTEGRADO = 'Other';
    public const CONFERENCIA_FISICA = 'Other';
    public const ARMAZENADO = 'Other';
    public const DESPACHADO = 'Departure';
    public const LOCALIDADE_NAO_ATENDIDA = 'Other';
    public const CLIENTE_AUSENTE = 'Other';
    public const PEDIDO_DANIFICADO = 'Other';
    public const ENTREGA_CANCELADA = 'Other';
    public const PEDIDO_EXTRAVIADO = 'Other';
    public const ENTREGA_REALIZADA_COM_SUCESSO = 'Departure';
    public const COLETA_REALIZADA = 'Other';
    public const PEDIDO_RECUSADO_CLIENTE = 'Other';
    public const ESTABELECIMENTO_FECHADO = 'Other';
    public const ENDERECO_NAO_LOCALIZADO = 'Other';
    public const CANCELADO_PELO_CLIENTE = 'Other';
    public const CLIENTE_INEXISTENTE = 'Other';
    public const OUTROS = 'Other';
    public const DEVOLVIDO = 'Other';
    public const NUMERO_NAO_LOCALIZADO = 'Other';
    public const INICIO_DE_DESLOCAMENTO_AO_CLIENTE = 'Other';
    public const COLETA_DE_RECEITA_REALIZADA = 'Other';
    public const EM_ROTA_DE_COLETA = 'Other';
    public const ENTREGA_DE_RECEITA_NA_FARMACIA = 'Other';
    public const COLETA_PEDIDOS = 'Other';
    public const NAO_COLETADO = 'Other';
    public const TRANSBORDO = 'Other';
    public const CHEGADA_NA_COLETA = 'Arrival';
    public const CHEGADA_NA_ENTREGA = 'Arrival';
    public const AGUARDANDO_RETIRADA_DO_CLIENTE = 'Other';
    public const ACAREAMENTO_EXTRAVIO = 'Other';
    public const EM_PROCESSO_DE_DEVOLUCAO = 'Other';
    public const DEVOLVIDO_POR_EXTRAVIO = 'Other';
    public const ACAREAMENTO_FINALIZADO_EXTRAVIO = 'Other';
    public const RETENCAO_FISCAL = 'Other';
    public const REJEITADO_PELA_TRANSPORTADORA = 'Other';
    public const POSICAO_ATUALIZADA_GPS = 'Check_Call';
    public const ENTREGADOR_NAO_LOCALIZADO = 'Other';
    public const PROCURANDO_ENTREGADOR = 'Other';
    public const COTACAO = 'Other';
}
