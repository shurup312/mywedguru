<?php

namespace infrastructure\common\components;

class SqlRepository
{
    /**
     * @return \yii\db\Connection
     */
    protected function db()
    {
        return \Yii::$app->getDb();
    }
}