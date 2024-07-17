<?php

namespace BeeDelivery\RD\Utils;

trait MessageTypeRD
{
    public const MT_INTEGRATED = 'Other';
    public const MT_PHYSICAL_CONFERENCE = 'Other';
    public const MT_STORED = 'Other';
    public const MT_DISPATCHED = 'Departure';
    public const MT_UNATTENDED_LOCALITY = 'Other';
    public const MT_CLIENT_ABSENT = 'Other';
    public const MT_DAMAGED_ORDER = 'Other';
    public const MT_CANCELED_DELIVERY = 'Other';
    public const MT_LOST_ORDER = 'Other';
    public const MT_SUCCESSFUL_DELIVERY = 'Departure';
    public const MT_PICKUP_COMPLETED = 'Other';
    public const MT_ORDER_REFUSED_BY_CLIENT = 'Other';
    public const MT_ESTABLISHMENT_CLOSED = 'Other';
    public const MT_ADDRESS_NOT_FOUND = 'Other';
    public const MT_CANCELED_BY_CLIENT = 'Other';
    public const MT_NONEXISTENT_CLIENT = 'Other';
    public const MT_OTHERS = 'Other';
    public const MT_RETURNED = 'Other';
    public const MT_NUMBER_NOT_FOUND = 'Other';
    public const MT_STARTING_TRAVEL_TO_CLIENT = 'Other';
    public const MT_PRESCRIPTION_PICKUP_COMPLETED = 'Other';
    public const MT_ON_PICKUP_ROUTE = 'Other';
    public const MT_PRESCRIPTION_DELIVERY_AT_PHARMACY = 'Other';
    public const MT_ORDER_PICKUP = 'Other';
    public const MT_NOT_COLLECTED = 'Other';
    public const MT_TRANSSHIPMENT = 'Other';
    public const MT_ARRIVAL_AT_PICKUP = 'Arrival';
    public const MT_ARRIVAL_AT_DELIVERY = 'Arrival';
    public const MT_WAITING_FOR_CLIENT_PICKUP = 'Other';
    public const MT_LOSS_RECONCILIATION = 'Other';
    public const MT_IN_RETURN_PROCESS = 'Other';
    public const MT_RETURNED_DUE_TO_LOSS = 'Other';
    public const MT_LOSS_RECONCILIATION_COMPLETED = 'Other';
    public const MT_FISCAL_RETENTION = 'Other';
    public const MT_REJECTED_BY_CARRIER = 'Other';
    public const MT_GPS_POSITION_UPDATED = 'Check_Call';
    public const MT_DELIVERER_NOT_FOUND = 'Other';
    public const MT_SEARCHING_FOR_DELIVERER = 'Other';
    public const MT_QUOTATION = 'Other';
    public const MT_DELIVERYMAN_ABANDONED_THE_ORDER = 'Other';
}
