<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 18:43
 */
namespace domain\person\specifications;

use domain\person\entities\Person;

class IsSetStudioSpecification
{
    const EMPTY_STUDIO_ID = 1;

    public static function withoutStudio(Person $aPerson)
    {
        return (int)$aPerson->studioId() === self::EMPTY_STUDIO_ID;
    }
}
