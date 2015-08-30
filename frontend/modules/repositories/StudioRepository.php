<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use app\modules\aggregates\StudioAggregate;

class StudioRepository
{
    public static function getById($id){
        $result = new StudioAggregate();
        /**
         * TODO:
         */
        return $result;
    }
}
