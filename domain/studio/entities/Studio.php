<?php
namespace domain\studio\entities;

class Studio
{
    private $id;
    private $name;
    private $phone;
    private $address;

    /**
     * @param $aName
     * @param $aPhone
     * @param $anAddress
     */
    public function __construct($aName, $aPhone, $anAddress)
    {
        $this->name = $aName;
        $this->phone = $aPhone;
        $this->address = $anAddress;
    }

    /**
     * @return int
     */
    public function id()
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
    public function name()
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
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
    
}
