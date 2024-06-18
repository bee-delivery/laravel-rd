<?php

namespace BeeDelivery\RD\Utils;

trait TenderEnumRD
{
    public const TND_OUT_OF_COVERAGE = '01';
    public const TND_UNREGISTERED_BRANCH = '02';
    public const TND_NO_DELIVERER_IN_REGION = '03';
    public const TND_RISK_AREA = '04';
    public const TND_FAILED_TO_GEOLOCATE_DESTINATION = '05';
}
