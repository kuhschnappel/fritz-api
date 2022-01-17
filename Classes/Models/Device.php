<?php
//TODO: implement functionmbitmask has functions
//$mask=(integer)$attributes['functionbitmask'];
//$has_temperatur=($mask & (1<<8))>0;
//$has_switch=($mask & (1<<9))>0;

namespace Kuhschnappel\FritzApi\Models;

class Device
{
    /**
     * @var string $identifier
     */
    public $identifier;

    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $functionbitmask
     */
    public $functionbitmask;

    /**
     * @var string $fwversion
     */
    public $fwversion;

    /**
     * @var string $manufacturer
     */
    public $manufacturer;

    /**
     * @var string $productname
     */
    public $productname;

    /**
     * @var string $name
     */
    public $name;

		/**
     * @var string $present
     */
    public $present;

		/**
     * @var string $txbusy
     */
    public $txbusy;


    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFunctionbitmask()
    {
        return $this->functionbitmask;
    }

    /**
     * @param string $functionbitmask
     */
    public function setFunctionbitmask($functionbitmask)
    {
        $this->functionbitmask = $functionbitmask;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return string
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * @param string $productname
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;
    }

    /**
     * @return string
     */
    public function getFwversion()
    {
        return $this->fwversion;
    }


    /**
     * @param string $fwversion
     */
    public function setFwversion($fwversion)
    {
        $this->fwversion = $fwversion;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPresent()
    {
        return $this->present;
    }

    /**
     * @param string $present
     */
    public function setPresent($present)
    {
        $this->present = $present;
    }

    /**
     * @return string
     */
    public function getTxbusy()
    {
        return $this->txbusy;
    }

    /**
     * @param string $txbusy
     */
    public function setTxbusy($txbusy)
    {
        $this->txbusy = $txbusy;
    }


}
