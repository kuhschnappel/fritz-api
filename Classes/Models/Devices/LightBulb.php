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

    public function __construct($cfg)
    {

        parent::__construct($cfg);

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


    }


}