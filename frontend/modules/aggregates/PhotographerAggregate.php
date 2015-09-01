<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:55
 */
namespace app\modules\aggregates;

use app\modules\exceptions\AggregateException;
use frontend\models\Person;
use frontend\models\Photogallery;

/**
 * Class PhotographerAggregate
 *
 * @package app\modules\aggregates
 * @property Person               $person
 * @property StudioAggregate|null $studioAggregate
 * @property Photogallery|null    $photogallery
 */
class PhotographerAggregate extends Aggregate
{

    protected $studioAggregate;
    protected $photogalleryAggregate;

    public function __construct(Person $person, $studioAggregate, $photogallery)
    {
        $this->root = $person;
        if ($studioAggregate !== null && !($studioAggregate instanceof StudioAggregate)) {
            throw new AggregateException('Переданный параметр не является студией');
        }
        if ($photogallery !== null && !($photogallery instanceof PhotogalleryAggregate)) {
            throw new AggregateException('Переданный параметр не является фотогалереей');
        }
    }

    /**
     * @return Person
     */
    public function photographer()
    {
        return $this->root();
    }

    /**
     * @return StudioAggregate|null
     */
    public function studioAggregate()
    {
        return $this->studioAggregate;
    }

    /**
     * @return Photogallery|null
     */
    public function photogalleryAggregate()
    {
        return $this->photogalleryAggregate;
    }
}
