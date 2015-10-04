<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 04.10.2015
 * Time: 16:28
 */
namespace domain\person\values;

use domain\person\entities\Person;
use yii\helpers\Inflector;

class Slug
{

    private $value;

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function createForPerson(Person $aPerson)
    {
        $this->setValue(Inflector::slug($aPerson->firstName().' '.$aPerson->lastName()));
    }
}
