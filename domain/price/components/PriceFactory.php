<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace domain\price\components;

use domain\person\entities\Price;

class PriceFactory
{

    /**
     * @return Price
     */
    public function createEmpty()
    {
        $price = new Price();
        return $price;
    }
}
