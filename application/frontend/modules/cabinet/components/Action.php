<?php

namespace cabinet\components;

use infrastructure\person\components\PersonCommandBus;
use infrastructure\studio\components\StudioCommandBus;
use yii\base\Action as ActionBase;

class Action extends ActionBase
{

    /**
     * @return StudioCommandBus
     * @throws \yii\base\InvalidConfigException
     */
    protected function getStudioCommandBus()
    {
        return \Yii::$container->get('domain\studio\contracts\StudioCommandBus');
    }

    /**
     * @return PersonCommandBus
     * @throws \yii\base\InvalidConfigException
     */
    protected function getPersonCommandBus()
    {
        return \Yii::$container->get('domain\person\contracts\PersonCommandBus');
    }
}
