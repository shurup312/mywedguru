<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:55
 */
namespace app\ddd\person\aggregates;

use app\ddd\interfaces\Aggregate;
use frontend\models\Person;
use frontend\models\Wedding;

/**
 * Class BrideAggregate
 *
 * @package app\modules\aggregates
 * @property Person  $bride
 * @property Person  $groom
 * @property Wedding $wedding
 */
class BrideAggregate extends Aggregate
{
    protected $groom;
    protected $wedding;

    /**
     * @param Person  $bride
     * @param Person  $groom
     * @param Wedding $wedding
     */
    public function __construct(Person $bride, Person $groom, Wedding $wedding)
    {
        $this->root    = $bride;
        $this->groom   = $groom;
        $this->wedding = $wedding;
        return $this;
    }

    /**
     * @return Person
     */
    public function bride()
    {
        return $this->root();
    }

    public function groom()
    {
        return $this->groom;
    }

    public function wedding()
    {
        return $this->wedding;
    }
}
