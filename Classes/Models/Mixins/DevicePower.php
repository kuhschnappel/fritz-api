<?php
namespace Kuhschnappel\FritzApi\Models\Mixins;
use Kuhschnappel\FritzApi\Api;


trait DevicePower {

	/**
	 * @return boolean
	 */
	public function isOn()
	{
		return (boolean)$this->switch['state'];
	}

	public function toggle()
	{
		$this->switch['state'] = $this->simpleonoff['state'] = (int)!$this->isOn();
		Api::switchCmd('setsimpleonoff', ['ain' => $this->identifier, 'onoff' => 2]);
	}

	public function powerOff()
	{
		//TODO: switchstate und und simpleonof auf 0 setzen
		$this->switch['state'] = $this->simpleonoff['state'] = 0;
		Api::switchCmd('setsimpleonoff', ['ain' => $this->identifier, 'onoff' => 0]);
	}

	public function powerOn()
	{
		//TODO: switchstate und und simpleonof auf 1 setzen
		$this->switch['state'] = $this->simpleonoff['state'] = 1;
		Api::switchCmd('setsimpleonoff', ['ain' => $this->identifier, 'onoff' => 1]);

	}


}