<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.09.2015
 * Time: 18:51
 */
namespace domain\common\components;

class Entity
{

    public function asArray()
    {
        $result = [];
        $reflector = new \ReflectionClass($this);
        $array = $reflector->getProperties();
        foreach ($array as $property) {
            if($property->isProtected()){
                $result[$property->getName()] = $this->{$property->getName()}();
            }
        }
        return $result;
    }
}
