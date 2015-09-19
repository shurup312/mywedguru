<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 19.09.2015
 * Time: 17:26
 */
namespace infrastructure\common\components;

use infrastructure\person\components\PersonCommandBus;
use infrastructure\studio\components\StudioCommandBus;
use infrastructure\wedding\components\WeddingCommandBus;

class CommandBusList
{

    /**
     * @return StudioCommandBus
     * @throws \yii\base\InvalidConfigException
     */
    public static function getStudioCommanBus()
    {
        return \Yii::$container->get('domain\studio\contracts\StudioCommandBus');
    }
    /**
     * @return PersonCommandBus
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPersonCommanBus()
    {
        return \Yii::$container->get('domain\person\contracts\PersonCommandBus');
    }
    /**
     * @return WeddingCommandBus
     * @throws \yii\base\InvalidConfigException
     */
    public static function getWeddingCommanBus()
    {
        return \Yii::$container->get('domain\wedding\contracts\WeddingCommandBus');
    }
}
