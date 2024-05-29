<?php

namespace BeeDelivery\RD\Utils;

class TrackingEnumRD
{
    public const INTEGRATED = '1';
    public const PHYSICAL_CONFERENCE = '2';
    public const STORED = '3';
    public const DISPATCHED = '4';
    public const UNATTENDED_LOCALITY = '5';
    public const CLIENT_ABSENT = '6';
    public const DAMAGED_ORDER = '7';
    public const CANCELED_DELIVERY = '8';
    public const LOST_ORDER = '9';
    public const SUCCESSFUL_DELIVERY = '10';
    public const SUCCESSFUL_REVERSE_PICKUP = '11';
    public const ORDER_REFUSED_BY_CLIENT = '12';
    public const ESTABLISHMENT_CLOSED = '13';
    public const ADDRESS_NOT_FOUND = '14';
    public const CANCELED_BY_CLIENT = '15';
    public const NONEXISTENT_CLIENT = '16';
    public const OTHER_OCCURRENCE = '17';
    public const RETURNED = '19';
    public const NUMBER_NOT_FOUND = '20';
    public const ON_PICKUP_ROUTE = '21';
    public const PRESCRIPTION_PICKUP_COMPLETED = '22';
    public const ON_DELIVERY_ROUTE_PRESCRIPTION = '23';
    public const PRESCRIPTION_DELIVERY = '24';
    public const ORDER_PICKUP = '25';
    public const NOT_COLLECTED = '26';
    public const TRANSSHIPMENT = '27';
    public const ARRIVAL_AT_PICKUP = '28';
    public const ARRIVAL_AT_DELIVERY = '29';
    public const WAITING_FOR_CLIENT_PICKUP = '30';
    public const LOSS_RECONCILIATION = '31';
    public const IN_RETURN_PROCESS = '32';
    public const RETURNED_DUE_TO_LOSS = '33';
    public const LOSS_RECONCILIATION_COMPLETED = '34';
    public const FISCAL_RETENTION = '35';
    public const REJECTED_BY_CARRIER = '36';
    public const GPS_POSITION_UPDATED = '37';
    public const DELIVERER_NOT_FOUND = '38';
    public const SEARCHING_FOR_DELIVERER = '39';
    public const QUOTATION = '40';
}

class StopSeqRD
{
    public const INTEGRATED = '1';
    public const PHYSICAL_CONFERENCE = '1';
    public const STORED = '1';
    public const DISPATCHED = '1';
    public const UNATTENDED_LOCALITY = '1';
    public const CLIENT_ABSENT = '1';
    public const DAMAGED_ORDER = '1';
    public const CANCELED_DELIVERY = '1';
    public const LOST_ORDER = '1';
    public const SUCCESSFUL_DELIVERY = '2';
    public const PICKUP_COMPLETED = '1';
    public const ORDER_REFUSED_BY_CLIENT = '1';
    public const ESTABLISHMENT_CLOSED = '1';
    public const ADDRESS_NOT_FOUND = '1';
    public const CANCELED_BY_CLIENT = '1';
    public const NONEXISTENT_CLIENT = '1';
    public const OTHERS = '1';
    public const RETURNED = '1';
    public const NUMBER_NOT_FOUND = '1';
    public const STARTING_TRAVEL_TO_CLIENT = '1';
    public const PRESCRIPTION_PICKUP_COMPLETED = '1';
    public const ON_PICKUP_ROUTE = '1';
    public const PRESCRIPTION_DELIVERY_AT_PHARMACY = '1';
    public const ORDER_PICKUP = '1';
    public const NOT_COLLECTED = '1';
    public const TRANSSHIPMENT = '1';
    public const ARRIVAL_AT_PICKUP = '1';
    public const ARRIVAL_AT_DELIVERY = '2';
    public const WAITING_FOR_CLIENT_PICKUP = '1';
    public const LOSS_RECONCILIATION = '1';
    public const IN_RETURN_PROCESS = '1';
    public const RETURNED_DUE_TO_LOSS = '1';
    public const LOSS_RECONCILIATION_COMPLETED = '1';
    public const FISCAL_RETENTION = '1';
    public const REJECTED_BY_CARRIER = '1';
    public const GPS_POSITION_UPDATED = 'null';
    public const DELIVERER_NOT_FOUND = '1';
    public const SEARCHING_FOR_DELIVERER = '1';
    public const QUOTATION = '1';
}

class MessageTypeRD
{
    public const INTEGRATED = 'Other';
    public const PHYSICAL_CONFERENCE = 'Other';
    public const STORED = 'Other';
    public const DISPATCHED = 'Departure';
    public const UNATTENDED_LOCALITY = 'Other';
    public const CLIENT_ABSENT = 'Other';
    public const DAMAGED_ORDER = 'Other';
    public const CANCELED_DELIVERY = 'Other';
    public const LOST_ORDER = 'Other';
    public const SUCCESSFUL_DELIVERY = 'Departure';
    public const PICKUP_COMPLETED = 'Other';
    public const ORDER_REFUSED_BY_CLIENT = 'Other';
    public const ESTABLISHMENT_CLOSED = 'Other';
    public const ADDRESS_NOT_FOUND = 'Other';
    public const CANCELED_BY_CLIENT = 'Other';
    public const NONEXISTENT_CLIENT = 'Other';
    public const OTHERS = 'Other';
    public const RETURNED = 'Other';
    public const NUMBER_NOT_FOUND = 'Other';
    public const STARTING_TRAVEL_TO_CLIENT = 'Other';
    public const PRESCRIPTION_PICKUP_COMPLETED = 'Other';
    public const ON_PICKUP_ROUTE = 'Other';
    public const PRESCRIPTION_DELIVERY_AT_PHARMACY = 'Other';
    public const ORDER_PICKUP = 'Other';
    public const NOT_COLLECTED = 'Other';
    public const TRANSSHIPMENT = 'Other';
    public const ARRIVAL_AT_PICKUP = 'Arrival';
    public const ARRIVAL_AT_DELIVERY = 'Arrival';
    public const WAITING_FOR_CLIENT_PICKUP = 'Other';
    public const LOSS_RECONCILIATION = 'Other';
    public const IN_RETURN_PROCESS = 'Other';
    public const RETURNED_DUE_TO_LOSS = 'Other';
    public const LOSS_RECONCILIATION_COMPLETED = 'Other';
    public const FISCAL_RETENTION = 'Other';
    public const REJECTED_BY_CARRIER = 'Other';
    public const GPS_POSITION_UPDATED = 'Check_Call';
    public const DELIVERER_NOT_FOUND = 'Other';
    public const SEARCHING_FOR_DELIVERER = 'Other';
    public const QUOTATION = 'Other';
}
