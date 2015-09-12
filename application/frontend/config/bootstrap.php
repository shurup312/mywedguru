<?php
// Studio
\Yii::$container->set('domain\studio\contracts\StudioRepository', 'infrastructure\studio\persistence\SqlStudioRepository');
\Yii::$container->set('domain\studio\contracts\StudioCommandBus', 'infrastructure\studio\components\StudioCommandBus');

//Person
\Yii::$container->set('domain\person\contracts\PersonCommandBus', 'infrastructure\person\components\PersonCommandBus');
