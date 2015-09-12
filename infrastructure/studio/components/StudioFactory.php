<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.08.2015
 * Time: 10:56
 */
namespace infrastructure\studio\components;

use domain\studio\entities\Studio;

class StudioFactory
{

    /**
     * @param $anAddress
     * @param $aName
     * @param $aPhone
     *
     * @return Studio
     */
    public function create($anAddress, $aName, $aPhone)
    {
        $studio = new Studio();
        $studio->setAddress($anAddress);
        $studio->setName($aName);
        $studio->setPhone($aPhone);
        return $studio;
    }
}
