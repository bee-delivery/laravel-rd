<?php

namespace BeeDelivery\RD;

class RD
{
    public function tenders($data)
    {
        return new Tenders($data);
    }

    public function trackings($data)
    {
        return new Trackings($data);
    }
}
