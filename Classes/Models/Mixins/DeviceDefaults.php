<?php

namespace Kuhschnappel\FritzApi\Models\Mixins;

use Kuhschnappel\FritzApi\Api;


trait DeviceDefaults
{


    /**
     * @return boolean
     */
    public function isConnected($cached = false)
    {
        if (!$cached)
            $this->fritzDeviceInfos->present = (string)Api::switchCmd('getswitchpresent', ['ain' => $this->getIdentifier()]);

        return (string)$this->fritzDeviceInfos->present;
    }

    /**
     * @return string
     */
    public function getName($cached = false)
    {
        if (!$cached)
            $this->fritzDeviceInfos->name = (string)Api::switchCmd('getswitchname', ['ain' => $this->getIdentifier()]);

        return (string)$this->fritzDeviceInfos->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        //TODO: check rights - 400er error
        Api::switchCmd('setswitchname', ['ain' => $this->getIdentifier(), 'name' => $name]);
        $this->fritzDeviceInfos->name = $name;
    }

    /**
     * @var string type to verify
     * @return boolean
     */
    public function isType($type)
    {
        if ($this->getType() == $type)
            return true;

        return false;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return end(explode('\\',get_class($this)));
    }

    /**
     * @param mixed measurements string or array
     * @return string
     * @todo implement caching
     */
    public function getStats($measurements = ['temperature', 'voltage', 'power', 'energy', 'humidity'])
    {
        $response = Api::switchCmd('getbasicdevicestats', ['ain' => $this->getIdentifier()]);
        $xml = simplexml_load_string($response);

        $measurementsArr = $measurements;
        if (!is_array($measurements))
            $measurementsArr = [$measurements];

        $statArr = [];
        foreach($measurementsArr as $measurement) {
            if (isset($xml->$measurement)) {
                $statArr[$measurement] = [];

                $dt_obj = new \DateTime("UTC");
                $ínterval = date_interval_create_from_date_string((string)$xml->$measurement->stats->attributes()->grid.' seconds');
                $values = explode(',', (string)$xml->$measurement->stats);
                foreach ($values as $value) {
                    date_add($dt_obj, $ínterval);
                    $statArr[$measurement][date_format($dt_obj, 'Y-m-d H:i:s')] = $value;
                }

            }
        }
        if(!is_array($measurements) && count($statArr)==1)
            return $statArr[$measurements];

        return $statArr;

    }







}