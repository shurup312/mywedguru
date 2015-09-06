<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 11.08.2015
 * Time: 0:26
 */
namespace frontend\models;

use app\ddd\interfaces\exceptions\ObjectValueException;

class UserType
{
    private $type;

    const USER_BRIDE = 1;
    const USER_PHOTOGRAPGER = 2;
    public static $prefix = [
        self::USER_BRIDE        => 'bride',
        self::USER_PHOTOGRAPGER => 'photographer',
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
}
