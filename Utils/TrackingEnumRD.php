<?php

namespace BeeDelivery\RD\Utils;

trait TrackingEnumRD
{
    public const TRK_INTEGRATED = '1';
    public const TRK_PHYSICAL_CONFERENCE = '2';
    public const TRK_STORED = '3';
    public const TRK_DISPATCHED = '4';
    public const TRK_UNATTENDED_LOCALITY = '5';
    public const TRK_CLIENT_ABSENT = '6';
    public const TRK_DAMAGED_ORDER = '7';
    public const TRK_CANCELED_DELIVERY = '8';
    public const TRK_LOST_ORDER = '9';
    public const TRK_SUCCESSFUL_DELIVERY = '10';
    public const TRK_SUCCESSFUL_REVERSE_PICKUP = '11';
    public const TRK_ORDER_REFUSED_BY_CLIENT = '12';
    public const TRK_ESTABLISHMENT_CLOSED = '13';
    public const TRK_ADDRESS_NOT_FOUND = '14';
    public const TRK_CANCELED_BY_CLIENT = '15';
    public const TRK_NONEXISTENT_CLIENT = '16';
    public const TRK_OTHER_OCCURRENCE = '17';
    public const TRK_RETURNED = '19';
    public const TRK_NUMBER_NOT_FOUND = '20';
    public const TRK_ON_PICKUP_ROUTE = '21';
    public const TRK_PRESCRIPTION_PICKUP_COMPLETED = '22';
    public const TRK_ON_DELIVERY_ROUTE_PRESCRIPTION = '23';
    public const TRK_PRESCRIPTION_DELIVERY = '24';
    public const TRK_ORDER_PICKUP = '25';
    public const TRK_NOT_COLLECTED = '26';
    public const TRK_TRANSSHIPMENT = '27';
    public const TRK_ARRIVAL_AT_PICKUP = '28';
    public const TRK_ARRIVAL_AT_DELIVERY = '29';
    public const TRK_WAITING_FOR_CLIENT_PICKUP = '30';
    public const TRK_LOSS_RECONCILIATION = '31';
    public const TRK_IN_RETURN_PROCESS = '32';
    public const TRK_RETURNED_DUE_TO_LOSS = '33';
    public const TRK_LOSS_RECONCILIATION_COMPLETED = '34';
    public const TRK_FISCAL_RETENTION = '35';
    public const TRK_REJECTED_BY_CARRIER = '36';
    public const TRK_GPS_POSITION_UPDATED = '37';
    public const TRK_DELIVERER_NOT_FOUND = '38';
    public const TRK_SEARCHING_FOR_DELIVERER = '39';
    public const TRK_QUOTATION = '40';
}
