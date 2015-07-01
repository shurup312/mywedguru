<?php
namespace webapp\modules\adm\models;

use system\core\Model;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 25.06.2015
 * Time: 14:33
 */
class Link extends Model
{

	public static $table = 'links';
	const TABLE_2_FIELDNAME = 'table2';
	const TABLE_1_FIELDNAME = 'table1';
	const ID_2_FIELDNAME = 'id2';
	const ID_1_FIELDNAME = 'id1';

	/**
	 * Получение ID связанных записей из tableName таблицы для объекта $entity
	 * При связи один ко многим данный метод применяется к стороне "один", то есть принимает первым параметром объект "один"
	 *
	 * @param Model  $entity
	 * @param string $tableName
	 *
	 * @return array
	 */
	public static function getLinksOneToMany(Model $entity, $tableName)
	{
		$reflector = new \ReflectionClass($entity);
		$table1    = $reflector->getStaticPropertyValue('table');
		return Link::factory()
				   ->where(self::TABLE_1_FIELDNAME, $table1)
				   ->where(self::TABLE_2_FIELDNAME, $tableName)
				   ->where(self::ID_1_FIELDNAME, $entity->id)
				   ->findPairs(false, self::ID_2_FIELDNAME);
	}

	/**
	 * Получение ID связанной записи из tableName таблицы для объекта $entity
	 * При связи один ко многим данный метод применяется к стороне "многие", то есть принимает первым параметром объект "многие"
	 *
	 * @param Model  $entity
	 * @param string $tableName
	 *
	 * @return array
	 */
	public static function getLinkOneToMany(Model $entity, $tableName)
	{
		$tableNameForEntity = self::getTableNameForEntity($entity);
		return Link::factory()
				   ->where(self::TABLE_1_FIELDNAME, $tableNameForEntity)
				   ->where(self::TABLE_2_FIELDNAME, $tableName)
				   ->where(self::ID_1_FIELDNAME, $entity->id)
				   ->findPairs(false, self::ID_2_FIELDNAME);
	}

	/**
	 * Получение ID связанной записи из tableName таблицы для объекта $entity
	 * При связи один ко многим данный метод применяется к стороне "многие", то есть принимает первым параметром объект "многие"
	 *
	 * @param Model  $entity
	 * @param string $tableName
	 *
	 * @return array
	 */
	public static function getLinkManyToOne(Model $entity, $tableName)
	{
		$tableNameForEntity = self::getTableNameForEntity($entity);
		return Link::factory()
				   ->where(self::TABLE_2_FIELDNAME, $tableNameForEntity)
				   ->where(self::TABLE_1_FIELDNAME, $tableName)
				   ->where(self::ID_2_FIELDNAME, $entity->id)
				   ->findPairs(false, self::ID_1_FIELDNAME);
	}

	/**
	 * Создание связи между двумя сущностями
	 *
	 * @param Model $entity1
	 * @param Model $entity2
	 *
	 * @return bool
	 */
	public static function createLink(Model $entity1, Model $entity2)
	{
		$reflector = new \ReflectionClass($entity1);
		$table1    = $reflector->getStaticPropertyValue('table');
		$reflector = new \ReflectionClass($entity2);
		$table2    = $reflector->getStaticPropertyValue('table');
		return Link::factory()
				   ->create(
					   [
						   self::TABLE_1_FIELDNAME => $table1,
						   self::TABLE_2_FIELDNAME => $table2,
						   self::ID_1_FIELDNAME    => $entity1->id,
						   self::ID_2_FIELDNAME    => $entity2->id
					   ]
				   )
				   ->save();
	}

	/**
	 * @param Model $entity
	 *
	 * @return mixed
	 */
	private static function getTableNameForEntity(Model $entity)
	{
		$reflector = new \ReflectionClass($entity);
		$table1    = $reflector->getStaticPropertyValue('table');
		return $table1;
	}
}
