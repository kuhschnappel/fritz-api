<?php
namespace Kuhschnappel\FritzApi\Models\Mixins;
use Kuhschnappel\FritzApi\Api;


trait DeviceTemperature {


	  /**
     * @return string
     */
    public function getRoomTemperature($unit = true)
    {
        return bcdiv($this->temperature['celsius'], 10, 1) . ($unit ? ' °C' : '');
    }


}