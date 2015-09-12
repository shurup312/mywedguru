<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:46
 */
namespace infrastructure\studio\commands;

use infrastructure\studio\components\StudioFactory;

class CreateStudioCommand
{
    private $studio;

    public function __construct($aName, $aPhone, $anAddress)
    {
        $this->studio = (new StudioFactory())->create($anAddress, $aName, $aPhone);
    }

    public function studio()
    {
        return $this->studio;
    }
}
