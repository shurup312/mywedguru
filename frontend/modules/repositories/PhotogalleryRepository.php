<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use app\modules\aggregates\ProtogalleryAggregate;

class PhotogalleryRepository
{
    public static function getByUserID($userID){
        $result = new ProtogalleryAggregate();
        /**
         * TODO:
         */
        return $result;
    }
}
