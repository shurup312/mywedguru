<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:02
 */
namespace domain\person\values;

use TypeSexException;

class Sex
{
    const MAX = 1;
    const WOMAN = 2;
    private static $typeList = [
        self::MAX   => 'Мужчина',
        self::WOMAN => 'Женщина',
    ];
    private $type;

    public function __construct($type)
    {
        if(!in_array(self::$typeList, $type)){
            throw new TypeSexException();
        }
        $this->type = $type;
    }

    public function type (){
        return $this->type;
    }
}
