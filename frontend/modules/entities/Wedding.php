<?php
namespace app\modules\entities;
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 28.08.2015
 * Time: 21:55
 */

/**
 * Class Wedding
 * @property integer $groomID привязка к Person
 * @property integer $brideID привязка к Person
 * @property $date
 */
class Wedding
{
    public $id;
    public $groomID;
    public $brideID;
    public $date;
}
