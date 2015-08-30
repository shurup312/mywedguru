<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:55
 */
namespace app\modules\aggregates;

use app\modules\entities\Wedding;
use frontend\models\Person;

/**
 * Class BrideAggregate
 * @package app\modules\aggregates
 * @property Person  $bride
 * @property Person  $groom
 * @property Wedding $wedding
 */
class BrideAggregate
{

    private $bride;
    private $groom;
    private $wedding;

    /**
     * @param Person  $bride
     * @param Person  $groom
     * @param Wedding $wedding
     */
    public function __construct(Person $bride, Person $groom, Wedding $wedding)
    {
        $this->bride   = $bride;
        $this->groom   = $groom;
        $this->wedding = $wedding;
        return $this;
    }

    /**
     * @return Person
     */
    public function bride()
    {
        return $this->bride;
    }
}
