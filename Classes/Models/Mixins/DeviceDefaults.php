<?php
namespace Kuhschnappel\FritzApi\Models\Mixins;
use Kuhschnappel\FritzApi\Api;


trait DeviceDefaults {

	/**
	 * @return boolean
	 */
	public function isConnected()
	{
		return (boolean)$this->getPresent();
	}


}