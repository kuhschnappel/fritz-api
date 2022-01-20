<?php

namespace Kuhschnappel\FritzApi\Models\Devices;

use Kuhschnappel\FritzApi\Api;
use Kuhschnappel\FritzApi\Models\Device;
use Kuhschnappel\FritzApi\Models\Mixins\DeviceDefaults;
use Kuhschnappel\FritzApi\Models\Mixins\DeviceTemperature;


// FRITZ!DECT 301
class Thermostat extends Device
{
    use DeviceDefaults;
    use DeviceTemperature;


    // const OFF = 0;
    // const ON = 1;
    // const TOGGLE = 2;

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
    private $switch;


    /**
     * @var array $simpleonoff
     *
     */
    private $simpleonoff;

    /**
     * @var array $temperature
     *
     */
    public $temperature;

    public function __construct($cfg)
    {

        parent::__construct($cfg);


        // SimpleXMLElement Object
        // (
        // 		[@attributes] => Array
        // 				(
        // 						[identifier] => 09995 0535352
        // 						[id] => 16
        // 						[functionbitmask] => 320
        // 						[fwversion] => 04.95
        // 						[manufacturer] => AVM
        // 						[productname] => FRITZ!DECT 301
        // 				)

        // 		[present] => 1
        // 		[txbusy] => 0
        // 		[name] => Heizung

        // 		[battery] => 80
        // 		[batterylow] => 0
        // 		[temperature] => SimpleXMLElement Object
        // 				(
        // 						[celsius] => 200
        // 						[offset] => 0
        // 				)

        // 		[hkr] => SimpleXMLElement Object
        // 				(
        // 						[tist] => 40
        // 						[tsoll] => 40
        // 						[absenk] => 32
        // 						[komfort] => 40
        // 						[lock] => 0
        // 						[devicelock] => 0
        // 						[errorcode] => 0
        // 						[windowopenactiv] => 0
        // 						[windowopenactiveendtime] => 0
        // 						[boostactive] => 0
        // 						[boostactiveendtime] => 0
        // 						[batterylow] => 0
        // 						[battery] => 80
        // 						[nextchange] => SimpleXMLElement Object
        // 								(
        // 										[endperiod] => 1642534200
        // 										[tchange] => 32
        // 								)

        // 						[summeractive] => 0
        // 						[holidayactive] => 0
        // 				)

        // )

        print_r($cfg);


    }

    /**
     * @return float in °C
     */
    public function temperatureSoll($temperatureSoll = null)
    {
        //TODO: check if is plugged in (present)
        //TODO: switchstate und und simpleonof auf 1 setzen
        if ($temperatureSoll)
            Api::switchCmd('sethkrtsoll', ['ain' => $this->identifier, 'param' => ($temperatureSoll * 2)]);
        else
            $temperatureSoll = Api::switchCmd('gethkrtsoll', ['ain' => $this->identifier]);
        return bcdiv($temperatureSoll, 2, 1);
    }


}