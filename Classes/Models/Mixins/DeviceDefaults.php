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

}