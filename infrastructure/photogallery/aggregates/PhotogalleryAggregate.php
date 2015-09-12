<?php
namespace app\ddd\photogallery\aggregates;
/**
 * Created by PhpStorm.
 * User: Shurup
 * Date: 29.08.2015
 * Time: 13:50
 */
use infrastructure\common\Aggregate;
use frontend\models\Photogallery;

/**
 * Class UserAggregate
 *
 * @property Photogallery $root
 * @property Photo[] $photoList
 */
class PhotogalleryAggregate extends Aggregate
{
    protected $photoList;
    public function __construct(Photogallery $photogallery, $photo)
    {
        $this->root = $photogallery;
//        if($photogallery !== null && !($photogallery instanceof Photogallery)){
//            throw new AggregateException('Переданный параметр не является фотогалереей');
//        }
    }

    public function photogallery()
    {
        return $this->root();
    }
}
