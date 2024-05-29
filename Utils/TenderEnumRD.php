<?php

namespace BeeDelivery\RD\Utils;

class TenderEnumRD
{
    public const OUT_OF_COVERAGE = '01';
    public const UNREGISTERED_BRANCH = '02';
    public const NO_DELIVERER_IN_REGION = '03';
    public const RISK_AREA = '04';
    public const FAILED_TO_GEOLOCATE_DESTINATION = '05';
}
