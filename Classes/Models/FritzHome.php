<?php
//https://avm.de/fileadmin/user_upload/Global/Service/Schnittstellen/AHA-HTTP-Interface.pdf
//https://www.heise.de/select/ct/2016/7/1459414791794586 - info zur bitmask
namespace Kuhschnappel\FritzApi\Models;

use Kuhschnappel\FritzApi\Api;
use Kuhschnappel\FritzApi\Models\Devices\SmartPlug;

class FritzHome
{
    /**
     * @var \Kuhschnappel\FritzApi\Api
     */
    protected static $api;

    /**
     * @var array set of SmartPlug Devices
     */
    protected static $smartPlugs;


    /**
     * @return array
     */
    public static function getSmartPlugs($refresh = false)
    {
        if($refresh)
            self::getDevices($refresh);

        return self::$smartPlugs;
    }


    public static function getDevices($refresh = false)
    {
        /*if (!self::$devices)
            self::$devices = new Devices();*/
        if ($refresh)
        {
            self::$smartPlugs = [];
        }

        // $xml = API::getDeviceListInfos();

				$response = Api::switchCmd('getdevicelistinfos');

				// $response = self::curlApiRoute(API::ROUTE_SWITCH . 'getdevicelistinfos&sid='.self::getSession());
				$xml = simplexml_load_string($response);

        foreach ($xml->device as $dev)
        {

            echo $dev->attributes()->productname;
            switch ($dev->attributes()->productname) {
                case 'FRITZ!DECT 210':
                    if ($smartPlug = new SmartPlug($dev))
                        self::$smartPlugs[] = $smartPlug;
                    break;
            }

        }

//        var_dump($xml);

    }


}