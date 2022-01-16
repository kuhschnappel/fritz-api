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
    protected $api;

    /**
     * @var array set of SmartPlug Devices
     */
    protected $smartPlugs;

    /**
     * @param string $host fritz box hostname e.g. http://192.168.178.1, http://fritz.box
     * @param string $user fritz box username with rights to use Smart Home
     * @param string $password fritz box usernames password
     */
    public function __construct($user = false, $password = false, $host = 'http://192.168.178.1')
    {
        $this->api = new Api($user, $password, $host);
    }

    /**
     * @return array
     */
    public function getSmartPlugs($refresh = false)
    {
        if($refresh)
            $this->getDevices($refresh);

        return $this->smartPlugs;
    }


    public function getDevices($refresh = false)
    {
        /*if (!$this->devices)
            $this->devices = new Devices();*/
        if ($refresh)
        {
            $this->smartPlugs = [];
        }

        $xml = $this->api->getDeviceListInfos();

        foreach ($xml->device as $dev)
        {
            echo '<hr>';

            echo $dev->attributes()->productname;
            switch ($dev->attributes()->productname) {
                case 'FRITZ!DECT 210':
                    if ($smartPlug = new SmartPlug($dev))
                        $this->smartPlugs[] = $smartPlug;
echo "JAS";
                    break;
            }

//            echo '<h3>Attribute</h3>';
//            foreach($dev->attributes() as $a => $b) {
//                echo '<strong>' . $a . ': </strong>' . $b . '<br>';
//            }
//
//
//
//            echo '<h3>Sonstiges:</h3>';
//            foreach ([
//                         'present',
//                         'txbusy',
//                         'battery',
//                         'name',
//                         'switch' => ['state','mode','lock','devicelock'],
//                         'simpleonoff' => ['state'],
//                         'batterylow',
//                         'temperature' => ['celsius', 'offset'],
//                         //'hkr'
//
//                         ] as $key => $val) {
//
//
//                if (!isset($dev->$val))
//                    continue;
//
//                if (is_array($val))
//                {
//
//                    foreach ($val as $v) {
//
//                        echo '<strong>' . $v . ':: </strong>' .$dev->$key->$v . '<br>';
//
//                    }
//
//                }
//                else
//                    echo '<strong>' . $val . ': </strong>' .$dev->$val . '<br>';
//
////                var_dump($val);
//
//            }

        }

//        var_dump($xml);
//        echo "devs!!!<hr><hr><hr>";
        var_dump($this->smartPlugs);
    }


}