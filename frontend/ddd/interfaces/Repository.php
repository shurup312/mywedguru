<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.08.2015
 * Time: 12:16
 */
namespace frontend\ddd\interfaces;

abstract class Repository
{

    /**
     * @return \yii\db\Connection
     */
    protected function getDb()
    {
        return \Yii::$app->getDb();
    }
}
