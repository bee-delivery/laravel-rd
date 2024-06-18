<?php

namespace BeeDelivery\RD\Utils;

trait StopSeqRD
{
    public const STQ_INTEGRATED = '1';
    public const STQ_PHYSICAL_CONFERENCE = '1';
    public const STQ_STORED = '1';
    public const STQ_DISPATCHED = '1';
    public const STQ_UNATTENDED_LOCALITY = '1';
    public const STQ_CLIENT_ABSENT = '1';
    public const STQ_DAMAGED_ORDER = '1';
    public const STQ_CANCELED_DELIVERY = '1';
    public const STQ_LOST_ORDER = '1';
    public const STQ_SUCCESSFUL_DELIVERY = '2';
    public const STQ_PICKUP_COMPLETED = '1';
    public const STQ_ORDER_REFUSED_BY_CLIENT = '1';
    public const STQ_ESTABLISHMENT_CLOSED = '1';
    public const STQ_ADDRESS_NOT_FOUND = '1';
    public const STQ_CANCELED_BY_CLIENT = '1';
    public const STQ_NONEXISTENT_CLIENT = '1';
    public const STQ_OTHERS = '1';
    public const STQ_RETURNED = '1';
    public const STQ_NUMBER_NOT_FOUND = '1';
    public const STQ_STARTING_TRAVEL_TO_CLIENT = '1';
    public const STQ_PRESCRIPTION_PICKUP_COMPLETED = '1';
    public const STQ_ON_PICKUP_ROUTE = '1';
    public const STQ_PRESCRIPTION_DELIVERY_AT_PHARMACY = '1';
    public const STQ_ORDER_PICKUP = '1';
    public const STQ_NOT_COLLECTED = '1';
    public const STQ_TRANSSHIPMENT = '1';
    public const STQ_ARRIVAL_AT_PICKUP = '1';
    public const STQ_ARRIVAL_AT_DELIVERY = '2';
    public const STQ_WAITING_FOR_CLIENT_PICKUP = '1';
    public const STQ_LOSS_RECONCILIATION = '1';
    public const STQ_IN_RETURN_PROCESS = '1';
    public const STQ_RETURNED_DUE_TO_LOSS = '1';
    public const STQ_LOSS_RECONCILIATION_COMPLETED = '1';
    public const STQ_FISCAL_RETENTION = '1';
    public const STQ_REJECTED_BY_CARRIER = '1';
    public const STQ_GPS_POSITION_UPDATED = 'null';
    public const STQ_DELIVERER_NOT_FOUND = '1';
    public const STQ_SEARCHING_FOR_DELIVERER = '1';
    public const STQ_QUOTATION = '1';
}