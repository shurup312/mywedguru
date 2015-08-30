<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:02
 */
namespace app\modules\valueObjects;

class Type
{

    const PHOTOGRAPHER = 1;
    const BRIDE = 2;
    private static $typeList = [
        self::PHOTOGRAPHER => 'Фотограф',
        self::BRIDE        => 'Невеста',
    ];
    private $type;

    public function __construct($type)
    {
        if (!in_array(self::$typeList, $type)) {
            throw new TypePeopleException();
        }
        $this->type = $type;
    }

    public function getPersonType()
    {
        return $this->type;
    }
}

class TypePeopleException extends \Exception
{

}
