<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 19.09.2015
 * Time: 14:21
 */
namespace domain\wedding\components;

use DateTime;
use domain\wedding\entities\Wedding;

class WeddingFactory
{

    public function create($aGroomId, $aBrideId, DateTime $aDate)
    {
        return new Wedding($aGroomId, $aBrideId, $aDate);
    }
}
