<?php
namespace Kuhschnappel\FritzApi\Models\Mixins;
use Kuhschnappel\FritzApi\Api;


trait DeviceTemperature {


	  /**
     * @return float in °C
     */
    public function temperature()
    {
        return bcdiv($this->temperature['celsius'], 10, 1);
    }


}