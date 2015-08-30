<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 16:32
 */
namespace app\modules\factories;
class BrideFactory
{
    public function __construct()
    {
        $this->createBride();
        $this->createGroom();
        $this->createWedding();
    }

    private function createBride()
    {
    }

    private function createGroom()
    {
    }

    private function createWedding()
    {
    }
}
/**
 * Как правило, атрибуты СУЩНОСТИ, не обязательные для ее идентификации, можно до­
 бавить позже, а не при создании.
 *
 * Конструкторов может быть несколько  - все кастомные, для разных параметров
 */
