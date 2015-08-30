<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 13:56
 */
namespace app\modules\aggregates;

use frontend\models\Studio;
use frontend\models\StudioOwner;

class StudioAggregate
{

    public $studio;
    private $studioOwner;

    public function __construct(Studio $studio, StudioOwner $studioOwner)
    {
        $this->studio      = $studio;
        $this->studioOwner = $studioOwner;
    }

    public function studio()
    {
        return $this->studio;
    }

    public function studioOwner()
    {
        return $this->studioOwner;
    }
}
