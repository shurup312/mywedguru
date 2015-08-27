<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 11.08.2015
 * Time: 0:26
 */
namespace frontend\models;

use yii\base\Model;

class UserType extends Model
{

    const USER_BRIDE = 1;
    const USER_PHOTOGRAPGER = 2;
    public static $prefix = [
        self::USER_BRIDE        => 'bride',
        self::USER_PHOTOGRAPGER => 'photographer',
    ];
}
