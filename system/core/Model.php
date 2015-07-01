<?php

namespace system\core;

use ArrayAccess, Exception;
use system\core\base\Object;

/**
 * MDB
 * Copyright (c) 2013, Erik Wiesenthal
 * All rights reserved
 * http://github.com/Surt/MDB/
 * A simple Active Record implementation built on top of Idiorm, Paris and Eloquent
 * ( http://github.com/Surt/MDB/ ).
 * You should include Idiorm before you include this file:
 * require_once 'your/path/to/idiorm.php';
 * BSD Licensed.
 * Copyright (c) 2010, Jamie Matthews
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 */
/**
 * Model base class. Your mod el objects should extend
 * this class. A minimal subclass would look like:
 * class Widget extends Model {
 * }

 */

/**
 * @method static Wrapper select()
 * @method static Wrapper create()
 * @method static Wrapper findOne()
 * @method static array|ResultSet findMany()
 * @method static Wrapper where()
 * @method static Wrapper whereIn()
 * @method static Wrapper rawWhere()
 * @method static Wrapper with()
 * @method static array findArray()
 * @method static Wrapper whereNotEqual()
 * @method static Wrapper rawQuery()
 */
class Model extends Object implements ArrayAccess
{

	// Default ID column for all models. Can be overridden by adding
	// a public static _id_column property to your model classes.
	const DEFAULT_ID_COLUMN = 'id';
	// Default foreign key suffix used by relationship methods
	const DEFAULT_FOREIGN_KEY_SUFFIX = '_id';
	protected static $resultSetClass = 'ResultSet';
	/**
	 * Set a prefix for model names. This can be a namespace or any other
	 * abitrary prefix such as the PEAR naming convention.
	 * @example Model::$auto_prefix_models = 'MyProject_MyModels_'; //PEAR
	 * @example Model::$auto_prefix_models = '\MyProject\MyModels\'; //Namespaces
	 * @var string
	 */
	protected static $auto_prefix_models = null;
	/**
	 * @var $orm ORM
	 * The ORM instance used by this model
	 * instance to communicate with the database.
	 */
	protected $orm;
	/**
	 * The model's $relationships attributes.
	 * $relationships attributes will not be saved to the database, and are
	 * primarily used to hold relationships.
	 * __set and __get need the relationship method defined on the model to determine if the relationship exists.
	 * @var array
	 */
	public $relationships = array ();
	/**
	 * The relationship type the model is currently resolving.
	 * @var string
	 */
	public $relating;
	/**
	 * The foreign key of the "relating" relationship.
	 * @var string
	 */
	public $relating_key;
	/**
	 * The table name of the model being resolved.
	 * This is used during has_many_through eager loading.
	 * @var string
	 */
	public $relating_table;

	/**
	 * Retrieve the value of a static property on a class. If the
	 * class or the property does not exist, returns the default
	 * value supplied as the third argument (which defaults to null).
	 *
	 * @param string $class_name
	 * @param string $property_name
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	protected static function getStaticProperty($class_name, $property_name, $default = null)
	{
		$property_value = $default;
		if (class_exists($class_name)) {
			$reflection = new \ReflectionClass($class_name);
			$properties = $reflection->getStaticProperties();
			array_key_exists($property_name, $properties) && $property_value = $properties[$property_name];
		}
		return $property_value;
	}

	/**
	 * Static method to get a table name given a class name.
	 * If the supplied class has a public static property
	 * named $table, the value of this property will be
	 * returned. If not, the class name will be converted using
	 * the _class_name_to_table_name method method.
	 *
	 * @param string $class_name
	 *
	 * @return string
	 */
	protected static function getTableName($class_name)
	{
		$specified_table_name = self::getStaticProperty($class_name, 'table');
		if (is_null($specified_table_name)) {
			return self::classNameToTableName($class_name);
		}
		return $specified_table_name;
	}

	/**
	 * Convert a namespace to the standard PEAR underscore format.
	 * Then convert a class name in CapWords to a table name in
	 * lowercase_with_underscores.
	 * Finally strip doubled up underscores
	 * For example, CarTyre would be converted to car_tyre. And
	 * Project\Models\CarTyre would be project_models_car_tyre.
	 *
	 * @param string $class_name
	 *
	 * @return string
	 */
	protected static function classNameToTableName($class_name)
	{
		return strtolower(
			preg_replace(
				array (
					'/\\\\/',
					'/(?<=[a-z])([A-Z])/',
					'/__/'
				), array (
					'_',
					'_$1',
					'_'
				), ltrim($class_name, '\\')
			)
		);
	}

	/**
	 * Return the ID column name to use for this class. If it is
	 * not set on the class, returns null.
	 *
	 * @param string $class_name
	 *
	 * @return string
	 */
	protected static function getIdColumnName($class_name)
	{
		return self::getStaticProperty($class_name, 'id_column', self::DEFAULT_ID_COLUMN);
	}

	/**
	 * Build a foreign key based on a table name. If the first argument
	 * (the specified foreign key column name) is null, returns the second
	 * argument (the name of the table) with the default foreign key column
	 * suffix appended.
	 *
	 * @param string $specified_foreign_key_name
	 * @param string $table_name
	 *
	 * @return string
	 */
	protected static function buildForeignKeyName($specified_foreign_key_name, $table_name)
	{
		if (!is_null($specified_foreign_key_name)) {
			return $specified_foreign_key_name;
		}
		return $table_name.self::DEFAULT_FOREIGN_KEY_SUFFIX;
	}

	/**
	 * Factory method used to acquire instances of the given class.
	 * The class name should be supplied as a string, and the class
	 * should already have been loaded by PHP (or a suitable autoloader
	 * should exist). This method actually returns a wrapped ORM object
	 * which allows a database query to be built. The wrapped ORM object is
	 * responsible for returning instances of the correct class when
	 * its find_one or find_many methods are called.
	 *
	 * @param bool|string $class_name
	 * @param string      $connection_name
	 *
	 * @return Wrapper
	 */
	public static function factory($class_name=false, $connection_name = null)
	{
		if(!$class_name){
			$class_name = get_called_class();
		}
		$class_name = self::$auto_prefix_models.$class_name;
		$table_name = self::getTableName($class_name);
		if ($connection_name==null) {
			$connection_name = self::getStaticProperty(
								   $class_name, 'connection_name', Wrapper::DEFAULT_CONNECTION
			);
		}
		$wrapper = Wrapper::forTable($table_name, $connection_name);
		$wrapper->setClassName($class_name);
		$wrapper->useIdColumn(self::getIdColumnName($class_name));
		$wrapper->resultSetClass = self::getStaticProperty($class_name, 'resultSetClass');
		$wrapper->setTableFields(self::getStaticProperty($class_name, 'table_fields'));
		return $wrapper;
	}

	/**
	 * Internal method to construct the queries for both the has_one and
	 * has_many methods. These two types of association are identical; the
	 * only difference is whether find_one or find_many is used to complete
	 * the method chain.
	 *
	 * @param string $associated_class_name
	 * @param string $foreign_key_name
	 * @param string $foreign_key_name_in_current_models_table
	 * @param string $connection_name
	 *
	 * @return ORM
	 */
	protected function hasOneOrMany($associated_class_name, $foreign_key_name = null, $foreign_key_name_in_current_models_table = null, $connection_name = null)
	{
		$base_table_name  = self::getTableName(get_class($this));
		$foreign_key_name = self::buildForeignKeyName($foreign_key_name, $base_table_name);
		/*
		 * $where_value - value of foreign_table.{$foreign_key_name} we're
		 * looking for. Where foreign_table is the actual
		 * database table in the associated model.
		 */
		if (is_null($foreign_key_name_in_current_models_table)) {
			//Match foreign_table.{$foreign_key_name} with the value of
			//{$this->_table}.{$this->id()}
			$where_value = $this->id();
		} else {
			//Match foreign_table.{$foreign_key_name} with the value of
			//{$this->_table}.{$foreign_key_name_in_current_models_table}
			$where_value = $this->$foreign_key_name_in_current_models_table;
		}
		// Added: to determine eager load relationship parameters
		$this->relating_key = $foreign_key_name;
		return self::factory($associated_class_name, $connection_name)
				   ->where($foreign_key_name, $where_value);
	}

	/**
	 * Helper method to manage one-to-one relations where the foreign
	 * key is on the associated table.
	 *
	 * @param string $associated_class_name
	 * @param string $foreign_key_name
	 * @param string $foreign_key_name_in_current_models_table
	 * @param string $connection_name
	 *
	 * @return ORM
	 */
	protected function hasOne($associated_class_name, $foreign_key_name = null, $foreign_key_name_in_current_models_table = null, $connection_name = null)
	{
		// Added: to determine eager load relationship parameters
		$this->relating = 'has_one';
		return $this->hasOneOrMany($associated_class_name, $foreign_key_name, $foreign_key_name_in_current_models_table, $connection_name);
	}

	/**
	 * Helper method to manage one-to-many relations where the foreign
	 * key is on the associated table.
	 *
	 * @param string $associated_class_name
	 * @param string $foreign_key_name
	 * @param string $foreign_key_name_in_current_models_table
	 * @param string $connection_name
	 *
	 * @return ORM
	 */
	protected function hasMany($associated_class_name, $foreign_key_name = null, $foreign_key_name_in_current_models_table = null, $connection_name = null)
	{
		// Added: to determine eager load relationship parameters
		$this->relating = 'has_many';
		return $this->hasOneOrMany($associated_class_name, $foreign_key_name, $foreign_key_name_in_current_models_table, $connection_name);
	}

	/**
	 * Helper method to manage one-to-one and one-to-many relations where
	 * the foreign key is on the base table.
	 *
	 * @param string $associated_class_name
	 * @param string $foreign_key_name
	 * @param string $foreign_key_name_in_associated_models_table
	 * @param string $connection_name
	 *
	 * @return ORM
	 */
	protected function belongsTo($associated_class_name, $foreign_key_name = null, $foreign_key_name_in_associated_models_table = null, $connection_name = null)
	{
		// Added: to determine eager load relationship parameters
		$this->relating = 'belongs_to';
		$associated_table_name = self::getTableName(self::$auto_prefix_models.$associated_class_name);
		$foreign_key_name      = self::buildForeignKeyName($foreign_key_name, $associated_table_name);
		$associated_object_id  = $this->$foreign_key_name;
		// Added: to determine eager load relationship parameters
		$this->relating_key = $foreign_key_name;
		$desired_record = null;
		if (is_null($foreign_key_name_in_associated_models_table)) {
			//"{$associated_table_name}.primary_key = {$associated_object_id}"
			//NOTE: primary_key is a placeholder for the actual primary key column's name
			//in $associated_table_name
			$desired_record = self::factory($associated_class_name, $connection_name)
								  ->whereIdIs($associated_object_id);
		} else {
			//"{$associated_table_name}.{$foreign_key_name_in_associated_models_table} = {$associated_object_id}"
			$desired_record = self::factory($associated_class_name, $connection_name)
								  ->where($foreign_key_name_in_associated_models_table, $associated_object_id);
		}
		return $desired_record;
	}

	/**
	 * Helper method to manage many-to-many relationships via an intermediate model. See
	 * README for a full explanation of the parameters.
	 *
	 * @param string $associated_class_name
	 * @param string $join_class_name
	 * @param string $key_to_base_table
	 * @param string $key_to_associated_table
	 * @param string $key_in_base_table
	 * @param string $key_in_associated_table
	 * @param string $connection_name
	 *
	 * @return ORM
	 */
	protected function hasManyThrough($associated_class_name, $join_class_name = null, $key_to_base_table = null, $key_to_associated_table = null, $key_in_base_table = null, $key_in_associated_table = null, $connection_name = null)
	{
		// Added: to determine eager load relationship parameters
		$this->relating = 'has_many_through';
		$base_class_name = get_class($this);
		// The class name of the join model, if not supplied, is
		// formed by concatenating the names of the base class
		// and the associated class, in alphabetical order.
		if (is_null($join_class_name)) {
			$model      = explode('\\', $base_class_name);
			$model_name = end($model);
			if (substr($model_name, 0, strlen(self::$auto_prefix_models))==self::$auto_prefix_models) {
				$model_name = substr($model_name, strlen(self::$auto_prefix_models), strlen($model_name));
			}
			$class_names = array (
				$model_name,
				$associated_class_name
			);
			sort($class_names, SORT_STRING);
			$join_class_name = join("", $class_names);
		}
		// Get table names for each class
		$base_table_name       = self::getTableName($base_class_name);
		$associated_table_name = self::getTableName(self::$auto_prefix_models.$associated_class_name);
		$join_table_name       = self::getTableName(self::$auto_prefix_models.$join_class_name);
		// Get ID column names
		$base_table_id_column       = (is_null($key_in_base_table))?self::getIdColumnName($base_class_name):$key_in_base_table;
		$associated_table_id_column = (is_null($key_in_associated_table))?self::getIdColumnName(self::$auto_prefix_models.$associated_class_name):$key_in_associated_table;
		// Get the column names for each side of the join table
		$key_to_base_table       = self::buildForeignKeyName($key_to_base_table, $base_table_name);
		$key_to_associated_table = self::buildForeignKeyName($key_to_associated_table, $associated_table_name);
		/*
			"   SELECT {$associated_table_name}.*
				  FROM {$associated_table_name} JOIN {$join_table_name}
					ON {$associated_table_name}.{$associated_table_id_column} = {$join_table_name}.{$key_to_associated_table}
				 WHERE {$join_table_name}.{$key_to_base_table} = {$this->$base_table_id_column} ;"
		*/
		// Added: to determine eager load relationship parameters
		$this->relating_key   = array (
			$key_to_base_table,
			$key_to_associated_table
		);
		$this->relating_table = $join_table_name;
		return self::factory($associated_class_name, $connection_name)
				   ->select("{$associated_table_name}.*")
				   ->join(
				   $join_table_name, array (
									   "{$associated_table_name}.{$associated_table_id_column}",
									   '=',
									   "{$join_table_name}.{$key_to_associated_table}"
								   )
			)
				   ->where("{$join_table_name}.{$key_to_base_table}", $this->$base_table_id_column)
				   ->nonAssociative();
	}

	/**
	 * Set the wrapped ORM instance associated with this Model instance.
	 *
	 * @param ORM $orm
	 *
	 * @return $this
	 */
	public function setOrm(ORM $orm)
	{
		$this->orm = $orm;
		return $this;
	}

	/**
	 * Magic getter method, allows $model->property access to data.
	 * Added: check for
	 *      get_{property_name} method defined in model and not null
	 *      missing_{property_name} method is null or undefined in model
	 *      fetched relationships
	 *      not loaded relationship. "lazy load" if method exists
	 *
	 * @param $property
	 *
	 * @return array|bool|ORM|ResultSet|null
	 */
	public function __get($property)
	{
		$result = $this->orm->get($property);
		if ($result!==null) {
			if (method_exists($this, $method = 'get_'.$property)) {
				return $this->$method($result);
			} else {
				return $result;
			}
		} elseif (method_exists($this, $method = 'missing_'.$property)) {
			return $this->$method();
		} elseif (array_key_exists($property, $this->relationships)) {
			return $this->relationships[$property];
		} elseif (method_exists($this, $property)) {
			if ($property!=self::getIdColumnName(get_class($this))) {
				/**
				 * @var $relation ORM
				 */
				$relation = $this->$property();
				return $this->relationships[$property] = (in_array(
					$this->relating, array (
									   'has_one',
									   'belongs_to'
								   )
				))?$relation->findOne():$relation->findMany();
			} else
				return null;
		} else {
			return null;
		}
	}

	/**
	 * Magic setter method, allows $model->property = 'value' access to data.
	 * Added: use Model methods to determine if a relationship exists and populate it on $relationships instead of properties
	 */
	public function __set($property, $value)
	{
		return $this->set($property, $value);
	}

	/**
	 * Magic isset method, allows isset($model->property) to work correctly.
	 *
	 * @param $property
	 *
	 * @return bool
	 */
	public function __isset($property)
	{
		return (array_key_exists($property, $this->relationships) || $this->orm->__isset($property) || method_exists($this, $method = 'get_'.$property) || method_exists($this, $method = 'missing_'.$property));
	}

	/**
	 * Getter method, allows $model->get('property') access to data
	 */
	public function get($property)
	{
		return $this->orm->get($property);
	}

	/**
	 * Setter method, allows $model->set('property', 'value') access to data.
	 *
	 * @param string|array $property
	 * @param string|null  $value
	 *
	 * @return $this
	 */
	public function set($property, $value = null)
	{
		if (!is_array($property)) {
			$property = array ($property => $value);
		}
		foreach ($property as $field => $val) {
			if (method_exists($this, $method = 'set'.$field)) {
				$property[$field] = $this->$method($val);
				$value            = null;
			} elseif (method_exists($this, $field)) {
				$this->relationships[$field] = $val;
			}
		}
		$this->orm->set($property, $value);
		return $this;
	}

	/**
	 * Setter method, allows $model->setExpr('property', 'value') access to data.
	 *
	 * @param string|array $property
	 * @param string|null  $value
	 *
	 * @return static
	 */
	public function setExpr($property, $value = null)
	{
		$this->orm->setExpr($property, $value);
		return $this;
	}

	/**
	 * ArrayAccess
	 *
	 * @param int|string $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return $this->__isset($offset);
	}

	/**
	 * ArrayAccess
	 *
	 * @param int|string $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->__get($offset);
	}

	/**
	 * ArrayAccess
	 *
	 * @param int|string $offset
	 * @param mixed      $value
	 *
	 * @return $this|Model
	 */
	public function offsetSet($offset, $value)
	{
		return $this->__set($offset, $value);
	}

	/**
	 * ArrayAccess
	 *
	 * @param int|string $offset
	 */
	public function offsetUnset($offset)
	{
		$this->orm->offsetUnset($offset);
	}

	/**
	 * Check whether the given field has changed since the object was created or saved
	 *
	 * @param $property
	 *
	 * @return bool
	 */
	public function isDirty($property)
	{
		return $this->orm->isDirty($property);
	}

	/**
	 * Check whether the model was the result of a call to create() or not
	 * @return bool
	 */
	public function isNew()
	{
		return $this->orm->isNew();
	}

	/**
	 * Wrapper for Idiorm's asArray method.
	 */
	public function asArray()
	{
		$args = func_get_args();
		return call_user_func_array(
			array (
				$this->orm,
				'asArray'
			), $args
		);
	}

	/**
	 * Save the data associated with this model instance to the database.
	 *
	 * @param bool $ignore
	 *
	 * @return bool
	 */
	public function save($ignore = false)
	{
		return $this->orm->save($ignore);
	}

	/**
	 * Delete the database row associated with this model instance.
	 */
	public function delete()
	{
		return $this->orm->delete();
	}

	/**
	 * Get the database ID of this model instance.
	 */
	public function id()
	{
		return $this->orm->id();
	}

	/**
	 * Hydrate this model instance with an associative array of data.
	 * WARNING: The keys in the array MUST match with columns in the
	 * corresponding database table. If any keys are supplied which
	 * do not match up with columns, the database will throw an error.
	 */
	public function hydrate($data)
	{
		$this->orm->hydrate($data)
				  ->forceAllDirty();
	}

	public function getResultSetClass()
	{
		return static::$resultSetClass;
	}

	/**
	 * Calls static methods directly on the Orm\Wrapper
	 *
	 * @param string $method
	 * @param array  $parameters
	 *
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$model = self::factory(get_called_class());
		return call_user_func_array(
			array (
				$model,
				$method
			), $parameters
		);
	}
}

/**
 * Subclass of Idiorm's ORM class that supports
 * returning instances of a specified class rather
 * than raw instances of the ORM class.
 * You shouldn't need to interact with this class
 * directly. It is used internally by the Model base
 * class.
 */
class Wrapper extends ORM
{

	/**
	 * The wrapped find_one and find_many classes will
	 * return an instance or instances of this class.
	 */
	protected $class_name;
	/**
	 * если массив не пуст, только эти поля будут сохранены в бд
	 * @var array
	 */
	protected $table_fields = array ();
	/**
	 * если массив $table_fields пуст, пытаться ли найти инфу о полях в бд
	 * @var bool
	 */
	protected $detect_table_fields = true;
	public $relationships = array ();

	public function setTableFields($array = array ())
	{
		$this->table_fields = $array;
	}

	public function setClassName($class_name)
	{
		$this->class_name = $class_name;
	}

	/**
	 * Add a custom filter to the method chain specified on the
	 * model class. This allows custom queries to be added
	 * to models. The filter should take an instance of the
	 * ORM wrapper as its first argument and return an instance
	 * of the ORM wrapper. Any arguments passed to this method
	 * after the name of the filter will be passed to the called
	 * filter function as arguments after the ORM class.
	 */
	public function filter()
	{
		$args            = func_get_args();
		$filter_function = array_shift($args);
		array_unshift($args, $this);
		if (method_exists($this->class_name, $filter_function)) {
			return call_user_func_array(
				array (
					$this->class_name,
					$filter_function
				), $args
			);
		}
		return $this;
	}

	/**
	 * Factory method, return an instance of this
	 * class bound to the supplied table name.
	 * A repeat of content in parent::for_table, so that
	 * created class is ORMWrapper, not ORM
	 *
	 * @param string $table_name
	 * @param string $connection_name
	 *
	 * @return ORM|Wrapper
	 */
	public static function forTable($table_name, $connection_name = parent::DEFAULT_CONNECTION)
	{
		self::setupConnection($connection_name);
		return new self($table_name, array (), $connection_name);
	}

	/**
	 * Method to create an instance of the model class
	 * associated with this wrapper and populate
	 * it with the supplied Idiorm instance.
	 *
	 * @param $orm
	 *
	 * @return Model
	 */
	protected function createModelInstance($orm)
	{
		if ($orm===false) {
			return false;
		}
		/**
		 * @var $model Model
		 */
		$model = new $this->class_name;
		/**
		 * @var $orm Wrapper
		 */
		$orm->resultSetClass = $model->getResultSetClass();
		$orm->setClassName($this->class_name);
		$model->setOrm($orm);
		return $model;
	}

	/**
	 * Overload select_expr name
	 *
	 * @param      $expr
	 * @param null $alias
	 *
	 * @return $this|ORM
	 */
	public function rawSelect($expr, $alias = null)
	{
		return $this->selectExpr($expr, $alias);
	}

	/**
	 * Special method to query the table by its primary key
	 */
	public function whereIdIn($ids)
	{
		return $this->whereIn($this->getIdColumnName(), $ids);
	}

	/**
	 * Create raw_join

	 */
	public function rawJoin($join)
	{
		$this->join_sources[] = "$join";
		return $this;
	}

	/**
	 * Add an unquoted expression to the list of columns to GROUP BY
	 */
	public function rawGroupBy($expr)
	{
		$this->group_by[] = $expr;
		return $this;
	}

	/**
	 * Add an unquoted expression as an ORDER BY clause
	 */
	public function rawOrderBy($clause)
	{
		$this->order_by[] = $clause;
		return $this;
	}

	/**
	 * To create and save multiple elements, easy way
	 * Using an array with rows array(array('name'=>'value',...), array('name2'=>'value2',...),..)
	 * or a array multiple

	 */
	public function insert($rows, $ignore = false)
	{
		ORM::getConnection()
		   ->beginTransaction();
		foreach ($rows as $row) {
			$class = $this->class_name;
			$class::create($row)
				  ->save($ignore);
		}
		ORM::getConnection()
		   ->commit();
		return ORM::getConnection()
				  ->lastInsertId();
	}

	/**
	 * Wrap Idiorm's find_one method to return
	 * an instance of the class associated with
	 * this wrapper instead of the raw ORM class.
	 * Added: hidrate the model instance before returning
	 */
	public function findOne($id = null)
	{
		$result = $this->createModelInstance(parent::findOne($id));
		if ($result) {
			// set result on an result set for the eager load to work
			$key     = (isset($result->{$this->instance_id_column}) && $this->associative_results)?$result->id():0;
			$results = array ($key => $result);
			Eager::hydrate($this, $results, self::$config[$this->connection_name]['return_result_sets']);
			// return the result as element, not result set
			$result = $results[$key];
		}
		return $result;
	}

	/**
	 * Tell the ORM that you are expecting multiple results
	 * from your query, and execute it. Will return an array
	 * of instances of the ORM class, or an empty array if
	 * no rows were returned.
	 * @return array|ResultSet
	 */
	public function findMany()
	{
		$instances = parent::findMany();
		return $instances?Eager::hydrate($this, $instances, self::$config[$this->connection_name]['return_result_sets']):$instances;
	}

	/**
	 * Override Idiorm _instances_with_id_as_key
	 * Create instances of each row in the result and map
	 * them to an associative array with the primary IDs as
	 * the array keys.
	 * Added: the array result key = primary key from the model
	 * Added: Eager loading of relationships defined "with()"
	 *
	 * @param array $rows
	 *
	 * @return array
	 */
	protected function getInstances($rows)
	{
		$instances = array ();
		foreach ($rows as $current_key => $current_row) {
			$row             = $this->createInstanceFromRow($current_row);
			$row             = $this->createModelInstance($row);
			$key             = (isset($row->{$this->instance_id_column}) && $this->associative_results)?$row->id():$current_key;
			$instances[$key] = $row;
		}
		return $instances;
	}

	/**
	 * Pluck a single column from the result.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	public function pluck($column)
	{
		$result = $this->select($column)
					   ->findOne();
		if ($result) {
			return $result[$column];
		} else {
			return null;
		}
	}

	/**
	 * Wrap Idiorm's create method to return an
	 * empty instance of the class associated with
	 * this wrapper instead of the raw ORM class.
	 */
	public function create($data = null)
	{
		$model = $this->createModelInstance(parent::create(null));
		if ($data!==null)
			$model->set($data);
		return $model;
	}

	/**
	 * Перед сохранением убираем лишние столбцы
	 *
	 * @param bool $ignore
	 *
	 * @return bool
	 */
	public function save($ignore = false)
	{
		if($this->table_fields){
		if ($allowed_fields = array_flip($this->table_fields)) {
			$this->data         = array_intersect_key($this->data, $allowed_fields);
			$this->dirty_fields = array_intersect_key($this->dirty_fields, $allowed_fields);
			$this->expr_fields  = array_intersect_key($this->expr_fields, $allowed_fields);
		};
		}
		return parent::save($ignore);
	}

	/**
	 * Added: Set the eagerly loaded models on the queryable model.
	 * @return Model
	 */
	public function with()
	{
		$this->relationships = array_merge($this->relationships, func_get_args());
		return $this;
	}

	/**
	 * Added: Reset relation deletes the relationship "where" condition.
	 * @return Model
	 */
	public function resetRelation()
	{
		array_shift($this->where_conditions);
		return $this;
	}

	/**
	 * Added: Return pairs as result array('keyrecord_value'=>'valuerecord_value',.....)

	 */
	public function findPairs($key = false, $value = false)
	{
		$key   = ($key)?$key:'id';
		$value = ($value)?$value:'name';
		return self::assocToKeyVal(
				   $this->rawSelect("$key,$value")
						->orderByAsc($value)
						->findArray(), $key, $value
		);
	}

	/**
	 * Converts a multi-dimensional associative array into an array of key => values with the provided field names
	 *
	 * @param   array  $assoc     the array to convert
	 * @param   string $key_field the field name of the key field
	 * @param   string $val_field the field name of the value field
	 *
	 * @return  array
	 */
	public static function assocToKeyVal($assoc = null, $key_field = null, $val_field = null)
	{
		if (empty($assoc) || empty($key_field) || empty($val_field)) {
			return null;
		}
		$output = array ();
		foreach ($assoc as $row) {
			if (isset($row[$key_field]) && isset($row[$val_field])) {
				$output[$row[$key_field]] = $row[$val_field];
			}
		}
		return $output;
	}

	/**
	 * Overrides __call to check for filter_$method names defined
	 * You can now define filters methods on the MDB Model as
	 * public static function filter{filterMethodName} and call it from a static call
	 * ModelName::filterMethodName->......
	 *
	 * @param string $method
	 * @param array  $parameters
	 *
	 * @throws Exception
	 */
	public function __call($method, $parameters)
	{
		if (method_exists($this->class_name, 'filter'.$method)) {
			array_unshift($parameters, $this);
			return call_user_func_array(
				array (
					$this->class_name,
					'filter'.$method
				), $parameters
			);
		} else {
			throw new Exception(" no static $method found or static method 'filter_$method' not defined in ".$this->class_name);
		}
	}
}

/**
 * @author    Erik Wiesenthal
 * @email erikwiesenthal@hotmail.com
 * @project   Paris / MDB
 * @copyright 2012
 * Mashed from eloquent https://github.com/taylorotwell/eloquent
 * to works with idiorm + http://github.com/j4mie/paris/

 */
class Eager
{

	/**
	 * Attempts to execute any relationship defined for eager loading
	 *
	 * @param ORM  $orm
	 * @param      $results
	 * @param bool $return_result_set
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public static function hydrate($orm, &$results, $return_result_set = false)
	{
		if (count($results) > 0) {
			foreach ($orm->relationships as $include) {
				$relationship      = false;
				$relationship_with = null;
				$relationship_args = array ();
				if (is_array($include)) {
					$relationship = key($include);
					if (isset($include[$relationship]['with'])) {
						$relationship_with = $include[$relationship]['with'];
						unset($include[$relationship]['with']);
					}
					$relationship_args = $include[$relationship];
				} else {
					$relationship = $include;
				}
				if ($pos = strpos($relationship, '.')) {
					$relationship_with = substr($relationship, $pos + 1, strlen($relationship));
					$relationship      = substr($relationship, 0, $pos);
					$relationship_args = array ();
				}
				$relationship = array (
					'name' => $relationship,
					'with' => $relationship_with,
					'args' => (array)$relationship_args
				);
				// check if relationship exists on the model
				$model = $orm->create();
				if (!method_exists($model, $relationship['name'])) {
					throw new Exception("Attempting to eager load [{$relationship['name']}], but the relationship is not defined.", '500');
				}
				self::eagerly($model, $results, $relationship, $return_result_set);
			}
		}
		return $results;
	}

	/**
	 * return the associative keys of a result set or the ids of an array of objects
	 *
	 * @param  ResultSet|array $parents ResultSet or Array to check for keys
	 *
	 * @return array           array of primary keys
	 */
	public static function getKeys($parents)
	{
		$keys    = array ();
		$parents = ($parents instanceof ResultSet)?$parents->asArray():$parents;
		if (key($parents)===0) {
			$count = count($parents);
			for ($i = 0;$i < $count;$i++) {
				$keys[] = $parents[$i]->id;
			}
		} else {
			$keys = array_keys($parents);
		}
		return $keys;
	}

	/**
	 * Eagerly load a relationship.
	 *
	 * @param  object $orm
	 * @param  object $result set
	 * @param  array  $parents
	 * @param  string $include
	 *
	 * @return void
	 */
	private static function eagerly($model, &$parents, $include, $return_result_set)
	{
		if ($relationship = call_user_func_array(
			array (
				$model,
				$include['name']
			), $include['args']
		)
		) {
			$relationship->resetRelation();
			if ($include['with'])
				$relationship->with($include['with']);
			// Initialize the relationship attribute on the parents. As expected, "many" relationships
			// are initialized to an array and "one" relationships are initialized to null.
			// added: many relationships are reset to array since we don't know yet the resultSet applicable
			foreach ($parents as &$parent) {
				$parent->relationships[$include['name']] = (in_array(
					$model->relating, array (
										'has_many',
										'has_many_through'
									)
				))?array ():null;
			}
			if (in_array(
				$relating = $model->relating, array (
												'has_one',
												'has_many',
												'belongs_to'
											)
			)
			) {
				$relating = str_replace('_', '', $relating);
				self::$relating($relationship, $parents, $model->relating_key, $include['name'], $return_result_set);
			} else {
				self::hasManyThrough($relationship, $parents, $model->relating_key, $model->relating_table, $include['name'], $return_result_set);
			}
		}
	}

	/**
	 * Eagerly load a 1:1 relationship.
	 *
	 * @param  object $relationship
	 * @param  array  $parents
	 * @param  string $relating_key
	 * @param  string $include
	 * @param  string $include
	 *
	 * @return void
	 */
	private static function hasOne($relationship, &$parents, $relating_key, $include, $return_result_set)
	{
		$keys    = static::getKeys($parents);
		$related = $relationship->whereIn($relating_key, $keys)
								->findMany();
		// if parents is not a associative array
		if (key(reset($parents))===0) {
			$results = array ();
			foreach ($related as $key => $child) {
				if (!isset($results[$child[$relating_key]])) {
					$results[$child[$relating_key]] = $child;
				}
			}
			foreach ($parents as $p_key => $parent) {
				foreach ($results as $r_key => $result) {
					if ($parent->id==$r_key) {
						$parents[$p_key]->relationships[$include] = $result;
					}
				}
			}
		} else {
			foreach ($related as $key => $child) {
				if (!isset($parents[$child->$relating_key]->relationships[$include])) {
					$parents[$child->$relating_key]->relationships[$include] = $child;
				}
			}
		}
	}

	/**
	 * Eagerly load a 1:* relationship.
	 *
	 * @param  object $relationship
	 * @param  array  $parents
	 * @param  string $relating_key
	 * @param  string $relating
	 * @param  string $include
	 *
	 * @return void
	 */
	private static function hasMany($relationship, &$parents, $relating_key, $include, $return_result_set)
	{
		$keys    = static::getKeys($parents);
		$related = $relationship->whereIn($relating_key, $keys)
								->findMany();
		// if parents is not a associative array
		if (key(reset($parents))===0) {
			$results = array ();
			foreach ($related as $key => $child) {
				if (empty($results[$child[$relating_key]]) && $return_result_set) {
					$resultSetClass                 = $child->getResultSetClass();
					$results[$child[$relating_key]] = new $resultSetClass();
				}
				$results[$child[$relating_key]][$child->id] = $child;
			}
			foreach ($parents as $p_key => $parent) {
				foreach ($results as $r_key => $result) {
					if ($parent->id==$r_key) {
						$parents[$p_key]->relationships[$include] = $result;
					}
				}
			}
		} else {
			// if parents is an associative array
			foreach ($related as $key => $child) {
				// if resultSet must be returned, create it if the relationships key is not defined
				if (empty($parents[$child[$relating_key]]->relationships[$include]) && $return_result_set) {
					$resultSetClass                                          = $child->getResultSetClass();
					$parents[$child->$relating_key]->relationships[$include] = new $resultSetClass();
				}
				// add the instance to the relationship array-resultSet
				$parents[$child->$relating_key]->relationships[$include][$child->id()] = $child;
			}
		}
	}

	/**
	 * Eagerly load a 1:1 belonging relationship.
	 *
	 * @param  object $relationship
	 * @param  array  $parents
	 * @param  string $relating_key
	 * @param  string $include
	 *
	 * @return void
	 */
	private static function belongsTo($relationship, &$parents, $relating_key, $include, $return_result_set)
	{
		foreach ($parents as &$parent) {
			$keys[] = $parent->$relating_key;
		}
		$children = $relationship->whereIdIn(array_unique($keys))
								 ->findMany();
		if ($children instanceof ResultSet)
			$children = $children->asArray();
		foreach ($parents as &$parent) {
			if (array_key_exists($parent->$relating_key, $children)) {
				$parent->relationships[$include] = $children[$parent->$relating_key];
			}
		}
	}

	/**
	 * Eagerly load a many-to-many relationship.
	 *
	 * @param  object $relationship
	 * @param  array  $parents
	 * @param  string $relating_key
	 * @param  string $relating_table
	 * @param  string $include
	 *
	 * @return void
	 */
	private static function hasManyThrough($relationship, &$parents, $relating_key, $relating_table, $include, $return_result_set)
	{
		$keys = static::getKeys($parents);
		// The foreign key is added to the select to allow us to easily match the models back to their parents.
		// Otherwise, there would be no apparent connection between the models to allow us to match them.
		$children = $relationship->select($relating_table.".".$relating_key[0])
								 ->whereIn($relating_table.'.'.$relating_key[0], $keys)
								 ->nonAssociative()
								 ->findMany();
		foreach ($children as $child) {
			$related = $child[$relating_key[0]];
			unset($child[$relating_key[0]]); // foreign key does not belongs to the related model
			if (empty($parents[$related]->relationships[$include]) && $return_result_set) {
				$resultSetClass                             = $child->getResultSetClass();
				$parents[$related]->relationships[$include] = new $resultSetClass();
			}
			// no associative result sets for has_many_through, so we can have multiple rows with the same primary_key
			$parents[$related]->relationships[$include][] = $child;
		}
	}
}
