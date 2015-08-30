<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:55
 */
namespace app\modules\aggregates;

use app\modules\entities\Photogallery;
use app\modules\exceptions\AggregateException;
use frontend\models\Person;

/**
 * Class PhotographerAggregate
 * @package app\modules\aggregates
 *
 * @property Person $person
 * @property StudioAggregate|null $studioAggregate
 * @property Photogallery|null $photogallery
 */
class PhotographerAggregate
{
    public $photographer;
    private $studioAggregate;
    private $photogallery;

    public function __construct(Person $person, $studioAggregate, $photogallery)
    {
        $this->photographer = $person;
        if($studioAggregate !== null && !($studioAggregate instanceof StudioAggregate)){
            throw new AggregateException('Переданный параметр не является студией');
        }
        if($photogallery !== null && !($photogallery instanceof Photogallery)){
            throw new AggregateException('Переданный параметр не является фотогалереей');
        }
    }

    public function photographer()
    {
        return $this->photographer;
    }

    public function studioAggregate()
    {
        return $this->studioAggregate;
    }

    public function photogallery()
    {
        return $this->photogallery;
    }
}
