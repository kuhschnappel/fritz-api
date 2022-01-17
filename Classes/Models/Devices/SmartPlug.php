<?php

namespace Kuhschnappel\FritzApi\Models\Devices;

use Kuhschnappel\FritzApi\Models\Device;


// FRITZ!DECT 210
class SmartPlug extends Device
{

    /**
     * @var array $powermeter
     * <power>Wert in 0,001 W (aktuelle Leistung, wird etwa alle 2 Minuten aktualisiert)
     * <energy>Wert in 1.0 Wh (absoluter Verbrauch seit Inbetriebnahme)
     * <voltage>Wert in 0,001 V (aktuelle Spannung, wird etwa alle 2 Minuten aktualisiert)
     */
    public $powermeter;

    /**
     * @var array $switch
     *
     */
    public $switch;


    /**
     * @var array $simpleonoff
     *
     */
    public $simpleonoff;

    /**
     * @var array $temperature
     *
     */
    public $temperature;

    public function __construct($cfg)
    {
//        SimpleXMLElement Object
//        (
//            [@attributes] => Array
//            (
//                [identifier] => 11657 0419500
//                [id] => 17
//                [functionbitmask] => 35712
//                [fwversion] => 04.17
//                [manufacturer] => AVM
//                [productname] => FRITZ!DECT 210
//            )
//            [present] => 1
//            [txbusy] => 0
//            [name] => Lara Fernseher
//            [switch] => SimpleXMLElement Object
//            (
//                [state] => 1
//                [mode] => manuell
//                [lock] => 0
//                [devicelock] => 0
//            )
//            [simpleonoff] => SimpleXMLElement Object
//            (
//                [state] => 1
//            )
//            [powermeter] => SimpleXMLElement Object
//            (
//                [voltage] => 233853
//                [power] => 1270
//                [energy] => 54345
//            )
//            [temperature] => SimpleXMLElement Object
//            (
//                [celsius] => 205
//                [offset] => 0
//            )
//        )

        //attributes
        $this->setIdentifier((string)$cfg->attributes()->identifier);
        $this->setFunctionbitmask((string)$cfg->attributes()->functionbitmask);
        $this->setFwversion((string)$cfg->attributes()->fwversion);
        $this->setManufacturer((string)$cfg->attributes()->manufacturer);
        $this->setProductname((string)$cfg->attributes()->productname);


        $this->setPresent((string)$cfg->present);
        $this->setTxbusy((string)$cfg->txbusy);
        $this->setName((string)$cfg->name);

        $this->setSwitch([
            'state'=>(string)$cfg->powermeter->state,
            'mode'=>(string)$cfg->powermeter->mode,
            'lock'=>(string)$cfg->powermeter->lock,
            'devicelock'=>(string)$cfg->powermeter->devicelock
        ]);

        $this->setSimpleonoff([
            'state'=>(string)$cfg->powermeter->voltage
        ]);

        $this->setPowermeter([
            'voltage'=>(string)$cfg->powermeter->voltage,
            'power'=>(string)$cfg->powermeter->power,
            'energy'=>(string)$cfg->powermeter->energy
        ]);

        $this->setTemperature([
            'celsius'=>(string)$cfg->powermeter->celsius,
            'offset'=>(string)$cfg->powermeter->offset
        ]);
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

    /**
     * @return array
     */
    public function getSwitch(): array
    {
        return $this->switch;
    }

    /**
     * @param array $switch
     */
    public function setSwitch(array $switch): void
    {
        $this->switch = $switch;
    }

    /**
     * @return array
     */
    public function getSimpleonoff(): array
    {
        return $this->simpleonoff;
    }

    /**
     * @param array $simpleonoff
     */
    public function setSimpleonoff(array $simpleonoff): void
    {
        $this->simpleonoff = $simpleonoff;
    }

    /**
     * @return array
     */
    public function getTemperature(): array
    {
        return $this->temperature;
    }

    /**
     * @param array $temperature
     */
    public function setTemperature(array $temperature): void
    {
        $this->temperature = $temperature;
    }


}