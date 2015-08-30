<?php
namespace app\modules\aggregates;
/**
 * Created by PhpStorm.
 * User: Shurup
 * Date: 29.08.2015
 * Time: 13:50
 */
use app\modules\entities\Photo;
use app\modules\entities\Photogallery;

/**
 * Class UserAggregate
 *
 * @property Photogallery $photogallery
 * @property Photo[] $photoList
 */
class ProtogalleryAggregate
{
    public $photogallery;
    private $photoList;

}
