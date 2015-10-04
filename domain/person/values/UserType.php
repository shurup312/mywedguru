<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 11.08.2015
 * Time: 0:26
 */
namespace domain\person\values;

use domain\common\exceptions\ObjectValueException;

class UserType
{
    private $type;

    const USER_BRIDE = 1;
    const USER_PHOTOGRAPGER = 2;
    const USER_GROOM = 3;
    public static $prefix = [
        self::USER_BRIDE        => 'bride',
        self::USER_PHOTOGRAPGER => 'photographer',
        self::USER_GROOM => 'groom',
    ];
    public static $typeName = [
        self::USER_BRIDE        => 'Невеста',
        self::USER_PHOTOGRAPGER => 'Фотограф',
        self::USER_GROOM => 'Жених',
    ];

    /**
     * @param integer $aType
     *
     * @throws ObjectValueException
     */
    public function __construct($aType){

        if(!array_key_exists($aType, self::$prefix)){
            throw new ObjectValueException();
        }
        $this->type = $aType;
    }

    /**
     * @return integer
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function prefix()
    {
        return self::$prefix[$this->type];
    }

    public function name()
    {
        return self::$typeName[$this->type];
    }
}
