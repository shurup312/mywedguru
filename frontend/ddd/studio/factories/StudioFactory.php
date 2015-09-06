<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.08.2015
 * Time: 10:56
 */
namespace app\ddd\studio\factories;

use app\ddd\studio\aggregates\StudioAggregate;
use frontend\models\Person;
use frontend\models\Studio;
use frontend\models\StudioOwner;

class StudioFactory
{
    /**
     * @return Studio
     */
    public function create()
    {
        $studio = new Studio();
        return $studio;
    }
}
