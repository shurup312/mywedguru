<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use app\modules\aggregates\BrideAggregate;

class BrideRepository
{
    public static function getById($id){
        $result = new BrideAggregate();
        /**
         * TODO:
         */
        return $result;
    }
}
