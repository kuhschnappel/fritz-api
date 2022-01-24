<?php

namespace Kuhschnappel\FritzApi\Models\Devices;

use Kuhschnappel\FritzApi\Api;
use Kuhschnappel\FritzApi\Models\Device;
use Kuhschnappel\FritzApi\Models\Mixins\DeviceDefaults;
use Kuhschnappel\FritzApi\Models\Mixins\DevicePower;


// FRITZ!DECT 500
class LightBulb extends Device
{

    use DeviceDefaults;
    use DevicePower;


//    public function __construct($cfg)
//    {
//
//        parent::__construct($cfg);

// SimpleXMLElement Object
// (
//     [@attributes] => Array
//         (
//             [identifier] => 13077 0000612-1
//             [id] => 2000
//             [functionbitmask] => 237572
//             [fwversion] => 0.0
//             [manufacturer] => AVM
//             [productname] => FRITZ!DECT 500
//         )
//     [present] => 0
//     [txbusy] => 0
//     [name] => Flur

//     [simpleonoff] => SimpleXMLElement Object
//         (
//             [state] => SimpleXMLElement Object
//                 (
//                 )
//         )
//     [levelcontrol] => SimpleXMLElement Object
//         (
//             [level] => SimpleXMLElement Object
//                 (
//                 )
//             [levelpercentage] => SimpleXMLElement Object
//                 (
//                 )
//         )
//     [colorcontrol] => SimpleXMLElement Object
//         (
//             [@attributes] => Array
//                 (
//                     [supported_modes] => 0
//                     [current_mode] =>
//                     [fullcolorsupport] => 0
//                     [mapped] => 0
//                 )

//             [hue] => SimpleXMLElement Object
//                 (
//                 )

//             [saturation] => SimpleXMLElement Object
//                 (
//                 )

//             [unmapped_hue] => SimpleXMLElement Object
//                 (
//                 )

//             [unmapped_saturation] => SimpleXMLElement Object
//                 (
//                 )

//             [temperature] => SimpleXMLElement Object
//                 (
//                 )

//         )

//     [etsiunitinfo] => SimpleXMLElement Object
//         (
//             [etsideviceid] => 408
//             [unittype] => 278
//             [interfaces] => 512,514,513
//         )

// )
// FRITZ!DECT 500SimpleXMLElement Object
// (
//     [@attributes] => Array
//         (
//             [identifier] => 13077 0040625
//             [id] => 409
//             [functionbitmask] => 1
//             [fwversion] => 34.10.16.16.015
//             [manufacturer] => AVM
//             [productname] => FRITZ!DECT 500
//         )

//     [present] => 0
//     [txbusy] => 0
//     [name] => Flur Tür
// )


//eingeschalten
// SimpleXMLElement Object
// (
//     [@attributes] => Array
//         (
//             [identifier] => 13077 0000612-1
//             [id] => 2000
//             [functionbitmask] => 237572
//             [fwversion] => 0.0
//             [manufacturer] => AVM
//             [productname] => FRITZ!DECT 500
//         )

//     [present] => 1
//     [txbusy] => 0
//     [name] => Flur
//     [simpleonoff] => SimpleXMLElement Object
//         (
//             [state] => 1
//         )

//     [levelcontrol] => SimpleXMLElement Object
//         (
//             [level] => 181
//             [levelpercentage] => 71
//         )

//     [colorcontrol] => SimpleXMLElement Object
//         (
//             [@attributes] => Array
//                 (
//                     [supported_modes] => 5
//                     [current_mode] => 4
//                     [fullcolorsupport] => 1
//                     [mapped] => 0
//                 )

//             [hue] => SimpleXMLElement Object
//                 (
//                 )

//             [saturation] => SimpleXMLElement Object
//                 (
//                 )

//             [unmapped_hue] => SimpleXMLElement Object
//                 (
//                 )

//             [unmapped_saturation] => SimpleXMLElement Object
//                 (
//                 )

//             [temperature] => 3000
//         )

//     [etsiunitinfo] => SimpleXMLElement Object
//         (
//             [etsideviceid] => 408
//             [unittype] => 278
//             [interfaces] => 512,514,513
//         )

// )


//4 neue switchcmd: setsimpleonoff, setlevel, setcolor, setcolortemperature
//getcolordefaults switchcmd hinzugefuegt

//    }

    /**
     * @return boolean
     */
    public function isOn()
    {
        return boolval((string)$this->fritzDeviceInfos->simpleonoff->state);
    }

    public function powerToggle()
    {
//        $this->fritzDeviceInfos->simpleonoff->state = (int)!$this->isOn();
        Api::switchCmd('setsimpleonoff', ['ain' => $this->getIdentifier(), 'onoff' => 2]);
        return $this->fritzDeviceInfos->simpleonoff->state = !$this->isOn();
    }

    public function powerOff()
    {
        //TODO: check if is plugged in (present)
        //TODO: switchstate und und simpleonof auf 0 setzen
        return $this->fritzDeviceInfos->simpleonoff->state = Api::switchCmd('setsimpleonoff', ['ain' => $this->getIdentifier(), 'onoff' => 0]);

    }

    public function powerOn()
    {
        //TODO: check if is plugged in (present)
        //TODO: switchstate und und simpleonof auf 1 setzen

        return $this->fritzDeviceInfos->simpleonoff->state = Api::switchCmd('setsimpleonoff', ['ain' => $this->getIdentifier(), 'onoff' => 1]);

    }


    /*
     * @return float level 0-100 in percent
     */
    public function getBrightness()
    {
        return (float) $this->fritzDeviceInfos->levelcontrol->levelpercentage;

    }

    /*
     * @var float level 0-100 in percent
     */
    public function setBrightness($level)
    {
        $this->fritzDeviceInfos->levelcontrol->levelpercentage = round($level);
        $level = round($level/100*255,0);
        return $this->fritzDeviceInfos->levelcontrol->level = Api::switchCmd('setlevel', ['ain' => $this->getIdentifier(), 'level' => $level]);
    }

    /*
     * @var int kelvin 2700 - 6500
     * @var int duration in ms
     */
    public function setColorTemperature($kelvin, $duration = 100)
    {
        if ($kelvin < 2700)
            $kelvin = 2700;
        if ($kelvin > 6500)
            $kelvin = 6500;

echo $kelvin;

//        $this->fritzDeviceInfos->levelcontrol->levelpercentage = round($level);
//        $level = round($level/100*255,0);
//        return $this->fritzDeviceInfos->levelcontrol->level =
            Api::switchCmd('setcolortemperature', ['ain' => $this->getIdentifier(), 'temperature' => $kelvin, 'duration' => $duration]);
    }

}