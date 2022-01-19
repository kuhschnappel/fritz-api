<?php
//https://avm.de/fileadmin/user_upload/Global/Service/Schnittstellen/AHA-HTTP-Interface.pdf
//https://www.heise.de/select/ct/2016/7/1459414791794586 - info zur bitmask
namespace Kuhschnappel\FritzApi\Models;

use Kuhschnappel\FritzApi\Api;
use Kuhschnappel\FritzApi\Models\Devices\SmartPlug;
use Kuhschnappel\FritzApi\Models\Devices\Thermostat;
use Kuhschnappel\FritzApi\Models\Devices\LightBulb;

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
     * @var array set of Thermostats Devices
     */
    protected static $thermostats;

    /**
     * @var array set of LightBulb Devices
     */
    protected static $lightBulbs;


    /**
     * @return array
     */
    public static function getSmartPlugs($refresh = false)
    {
        if($refresh)
            self::getDevices($refresh);
        return self::$smartPlugs;
    }

	  /**
     * @return array
     */
    public static function getThermostats($refresh = false)
    {
        if($refresh)
            self::getDevices($refresh);
        return self::$thermostats;
    }

	  /**
     * @return array
     */
    public static function getLightBulbs($refresh = false)
    {
        if($refresh)
            self::getDevices($refresh);
        return self::$lightBulbs;
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
            switch ($dev->attributes()->productname) {
                case 'FRITZ!DECT 210':
									$objectName = 'SmartPlug';
                    break;
								case 'FRITZ!DECT 301':
									$objectName = 'Thermostat';
									break;
								case 'FRITZ!DECT 500':
									$objectName = 'LightBulb';
									break;
								default:
									Api::$logger->warning('Unknown device, not implemented yet -> ' . $dev->attributes()->productname);
									break;
            }
						if ($objectName) {
							try {
								$model = '\\Kuhschnappel\\FritzApi\\Models\\Devices\\' . $objectName;
								$object = new $model($dev);
								self::addDevice($object);
							}
							catch( \Exception $e ) {
								Api::$logger->warning('DeviceInit -> ' . $e->getMessage());
							}

						}

        }

//        var_dump($xml);

    }

		public static function addDevice($device)
    {
			switch (get_class($device)) {
				case 'Kuhschnappel\FritzApi\Models\Devices\SmartPlug':
					self::$smartPlugs[] = $device;
					break;
				case 'Kuhschnappel\FritzApi\Models\Devices\Thermostat':
					self::$thermostats[] = $device;
					break;
				case 'Kuhschnappel\FritzApi\Models\Devices\LightBulb':
					self::$lightBulbs[] = $device;
					break;

			}
		}


}