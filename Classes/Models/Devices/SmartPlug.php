<?php

namespace Kuhschnappel\FritzApi\Models\Devices;

use Kuhschnappel\FritzApi\Models\Device;


// FRITZ!DECT 210
class SmartPlug extends Device
{

    /**
     * @var array $powermeter
     */
    public $powermeter;

    public function __construct($cfg)
    {
        echo '<hr><h1>INIT SmartPlug</h1>';
        var_dump($cfg);
        $this->setIdentifier((string)$cfg->attributes()->identifier);
        $this->setFunctionbitmask((string)$cfg->attributes()->functionbitmask);
        $this->setFwversion((string)$cfg->attributes()->fwversion);
        $this->setManufacturer((string)$cfg->attributes()->manufacturer);
        $this->setProductname((string)$cfg->attributes()->productname);

        echo $cfg->present.'<br>';
        echo $cfg->txbusy.'<br>';

        $this->setName((string)$cfg->name);

        echo $cfg->switch->state.'<br>';
        echo $cfg->switch->mode.'<br>';
        echo $cfg->switch->lock.'<br>';
        echo $cfg->switch->devicelock.'<br>';

        echo $cfg->simpleonoff->state.'<br>';

        echo $cfg->powermeter->voltage.'<br>'; //Volt
        echo $cfg->powermeter->power.'<br>'; //Leistung in Watt
        echo $cfg->powermeter->energy.'<br>';
//
//        <powermeter>
// <power>Wert in 0,001 W (aktuelle Leistung, wird etwa alle 2 Minuten aktualisiert)
// <energy>Wert in 1.0 Wh (absoluter Verbrauch seit Inbetriebnahme)
// <voltage>Wert in 0,001 V (aktuelle Spannung, wird etwa alle 2 Minuten aktualisiert)

        $this->setPowermeter([
            'voltage'=>(string)$cfg->powermeter->voltage,
            'power'=>(string)$cfg->powermeter->power,
            'energy'=>(string)$cfg->powermeter->energy
        ]);

        echo $cfg->temperature->celsius.'<br>';
        echo $cfg->temperature->offset.'<br>';
    }

		/**
     * @return array
     */
    public function getPowermeter()
    {
        return $this->powermeter;
    }

    /**
     * @param array $powermeter
     */
    public function setPowermeter($powermeter)
    {
        $this->powermeter = $powermeter;
    }

    /**
     * @return string
     */
    public function getCurrentPowerConsumption($unit = true)
    {
			return bcdiv($this->powermeter['power'], 1000, 3) . ($unit ? ' Watt' : '');
    }
}