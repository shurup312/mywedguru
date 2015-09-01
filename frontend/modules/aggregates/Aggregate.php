<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.08.2015
 * Time: 11:13
 */
namespace app\modules\aggregates;

use app\modules\exceptions\AggregateException;
use yii\base\Model;

/**
 * Class Aggregate
 *
 * @package app\modules\aggregates
 * @property Model $root
 */
abstract class Aggregate
{

    /**
     * @var Model $root
     */
    public $root;

    public function root()
    {
        return $this->root;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws AggregateException
     */
    public function load($data)
    {
        $this->assertRootEntity();
        $result         = $this->root->load($data);
        $paramsNameList = $this->getEntityParamsNames();
        foreach ($paramsNameList as $name) {
            $result = $result && $this->$name->load($data);
        }
        return $result;
    }

    /**
     * @param string $scenario
     *
     * @return bool
     * @throws AggregateException
     */
    public function validate($scenario)
    {
        $this->assertRootEntity();
        $result         = $this->root->validate($scenario);
        $paramsNameList = $this->getEntityParamsNames();
        foreach ($paramsNameList as $name) {
            $result = $result && $this->$name->validate($scenario);
        }
        return $result;
    }

    private function getEntityParamsNames()
    {
        $entitiesNametList = [];
        foreach ((new \ReflectionClass($this))->getProperties() as $parameter) {
            if ($parameter->isProtected() && $this->isEntity($this->{$parameter->getName()})) {
                $entitiesNametList[] = $parameter->getName();
            }
        }
        return $entitiesNametList;
    }

    /**
     * @param $parameter
     *
     * @return bool
     */
    private function isEntity($parameter)
    {
        return $parameter instanceof Model || $parameter instanceof Aggregate;
    }

    private function assertRootEntity()
    {
        if (!($this->root instanceof Model)) {
            throw new AggregateException('Корневыой элемент не является моделью');
        }
    }
}
