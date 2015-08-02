<?php
namespace system\core;

use ArrayAccess, Iterator, Countable, IteratorAggregate, ArrayIterator;
use PDO, PDOStatement;

/**
 *
 * ORM
 *
 * A completely refactored, to match PSR coding standards, version of Idiorm
 *
 * Idiorm
 *
 * http://github.com/j4mie/idiorm/
 *
 * A single-class super-simple database abstraction layer for PHP.
 * Provides (nearly) zero-configuration object-relational mapping
 * and a fluent interface for building basic, commonly-used queries.
 *
 * BSD Licensed.
 *
 * Copyright (c) 2010, Jamie Matthews
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
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
 *
 */
class ORM implements ArrayAccess, Iterator
{

    // ----------------------- //
    // --- CLASS CONSTANTS --- //
    // ----------------------- //

    // WHERE and HAVING condition array keys
    const CONDITION_FRAGMENT = 0;
    const CONDITION_VALUES = 1;

    const DEFAULT_CONNECTION = 'default';

    // Limit clause style
    const LIMIT_STYLE_TOP_N = "top";
    const LIMIT_STYLE_LIMIT = "limit";

    // ------------------------ //
    // --- CLASS PROPERTIES --- //
    // ------------------------ //

    // Class configuration
    protected static $default_config = array(
        'connection_string' => 'sqlite::memory:',
        'id_column' => 'id',
        'id_column_overrides' => array(),
        'error_mode' => PDO::ERRMODE_EXCEPTION,
        'username' => null,
        'password' => null,
        'driver_options' => null,
        'identifier_quote_character' => null, // if this is null, will be autodetected
        'limit_clause_style' => null, // if this is null, will be autodetected
        'logging' => false,
        'logger' => null,
        'caching' => false,
        'return_result_sets' => true,
        'find_many_primary_id_as_key' => true,
    );

    // Map of configuration settings
    protected static $config = array();

    // Map of database connections, instances of the PDO class
    protected static $connections = array();

    // Last query run, only populated if logging is enabled
    protected static $last_query;

    // Log of all queries run, mapped by connection key, only populated if logging is enabled
    protected static $query_log = array();

    // Query cache, only used if query caching is enabled
    protected static $query_cache = array();

    // Reference to previously used PDOStatement object to enable low-level access, if needed
    protected static $last_statement = null;

    // --------------------------- //
    // --- INSTANCE PROPERTIES --- //
    // --------------------------- //

    // Key name of the connections in self::$connections used by this instance
    protected $connection_name;

    // The name of the table the current ORM instance is associated with
    protected $table_name;

    // Alias for the table to be used in SELECT queries
    protected $table_alias = null;

    // Values to be bound to the query
    protected $values = array();

    // Columns to select in the result
    protected $result_columns = array('*');

    // Are we using the default result column or have these been manually changed?
    protected $using_default_result_columns = true;

    // Join sources
    protected $join_sources = array();

    // Should the query include a DISTINCT keyword?
    protected $distinct = false;

    // Is this a raw query?
    protected $is_raw_query = false;

    // The raw query
    protected $raw_query = '';

    // The raw query parameters
    protected $raw_parameters = array();

    // Array of WHERE clauses
    protected $where_conditions = array();

    // LIMIT
    protected $limit = null;

    // OFFSET
    protected $offset = null;

    // ORDER BY
    protected $order_by = array();

    // GROUP BY
    protected $group_by = array();

    // HAVING
    protected $having_conditions = array();

    // The data for a hydrated instance of the class
    protected $data = array();

    // Fields that have been modified during the
    // lifetime of the object
    protected $dirty_fields = array();

    // Fields that are to be inserted in the db raw
    protected $expr_fields = array();

    // Is this a new object (has create() been called)?
    protected $is_new = false;

    // Name of the column to use as the primary key for
    // this instance only. Overrides the config settings.
    protected $instance_id_column = null;

    // name of the ResultSet Object
    public $resultSetClass = 'ResultSet';

    // associative results flag
    protected $associative_results = true;


    // ---------------------- //
    // --- STATIC METHODS --- //
    // ---------------------- //

    /**
     * Pass configuration settings to the class in the form of
     * key/value pairs. As a shortcut, if the second argument
     * is omitted and the key is a string, the setting is
     * assumed to be the DSN string used by PDO to connect
     * to the database (often, this will be the only configuration
     * required to use Idiorm). If you have more than one setting
     * you wish to configure, another shortcut is to pass an array
     * of settings (and omit the second argument).
     * @param string $key
     * @param mixed $value
     * @param string $connection_name Which connection to use
     */
    public static function configure($key, $value = null, $connection_name = self::DEFAULT_CONNECTION)
    {
        self::setupConnectionConfig($connection_name); //ensures at least default config is set

        if (is_array($key)) {
            // Shortcut: If only one array argument is passed,
            // assume it's an array of configuration settings
            foreach ($key as $conf_key => $conf_value) {
                self::configure($conf_key, $conf_value, $connection_name);
            }
        } else {
            if (is_null($value)) {
                // Shortcut: If only one string argument is passed,
                // assume it's a connection string
                $value = $key;
                $key = 'connection_string';
            }
            self::$config[$connection_name][$key] = $value;
        }
    }

    /**
     * Retrieve configuration options by key, or as whole array.
     * @param string $key
     * @param string $connection_name Which connection to use
     */
    public static function getConfig($key = null, $connection_name = self::DEFAULT_CONNECTION)
    {
        if ($key) {
            return self::$config[$connection_name][$key];
        } else {
            return self::$config[$connection_name];
        }
    }

    /**
     * Delete all configs in _config array.
     */
    public static function resetConfig()
    {
        self::$config = array();
    }

    /**
     * Despite its slightly odd name, this is actually the factory
     * method used to acquire instances of the class. It is named
     * this way for the sake of a readable interface, ie
     * ORM::forTable('table_name')->findOne()-> etc. As such,
     * this will normally be the first method called in a chain.
     * @param string $table_name
     * @param string $connection_name Which connection to use
     * @return ORM
     */
    public static function forTable($table_name, $connection_name = self::DEFAULT_CONNECTION)
    {
        self::setupConnection($connection_name);
        return new self($table_name, array(), $connection_name);
    }

    /**
     * Set up the database connection used by the class
     * @param string $connection_name Which connection to use
     */
    protected static function setupConnection($connection_name = self::DEFAULT_CONNECTION)
    {
        if (!array_key_exists($connection_name, self::$connections) ||
            !is_object(self::$connections[$connection_name])
        ) {
            self::setupConnectionConfig($connection_name);
			$dsn        = 'mysql:host='.App::getConfig()['db']['host'].';dbname='.App::getConfig()['db']['name'].';charset='.App::getConfig()['db']['charset'];
			$connection = new PDO(
				$dsn,
				App::getConfig()['db']['user'],
				App::getConfig()['db']['pass'],
                self::$config[$connection_name]['driver_options']
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, self::$config[$connection_name]['error_mode']);
            self::setConnection($connection, $connection_name);
            ORM::rawExecute('set names '.App::getConfig()['db']['charset']);
        }
    }

    /**
     * Ensures configuration (mulitple connections) is at least set to default.
     * @param string $connection_name Which connection to use
     */
    protected static function setupConnectionConfig($connection_name)
    {
        if (!array_key_exists($connection_name, self::$config)) {
            self::$config[$connection_name] = self::$default_config;
        }
    }

    /**
     * Set the PDO object used by Idiorm to communicate with the database.
     * This is public in case the ORM should use a ready-instantiated
     * PDO object as its database connection. Accepts an optional string key
     * to identify the connection if multiple connections are used.
     * @param PDO $connection
     * @param string $connection_name Which connection to use
     */
    public static function setConnection($connection, $connection_name = self::DEFAULT_CONNECTION)
    {
        self::setupConnectionConfig($connection_name);
        self::$connections[$connection_name] = $connection;
        self::setupIdentifierQuoteCharacter($connection_name);
        self::setupLimitClauseStyle($connection_name);
    }

    /**
     * Delete all registered PDO objects in _Connection array.
     */
    public static function resetConnection()
    {
        self::$connections = array();
    }

    /**
     * Detect and initialise the character used to quote identifiers
     * (table names, column names etc). If this has been specified
     * manually using ORM::configure('identifier_quote_character', 'some-char'),
     * this will do nothing.
     * @param string $connection_name Which connection to use
     */
    protected static function setupIdentifierQuoteCharacter($connection_name)
    {
        if (is_null(self::$config[$connection_name]['identifier_quote_character'])) {
            self::$config[$connection_name]['identifier_quote_character'] =
                self::detectIdentifierQuoteCharacter($connection_name);
        }
    }

    /**
     * Detect and initialise the limit clause style ("SELECT TOP 5" /
     * "... LIMIT 5"). If this has been specified manually using
     * ORM::configure('limit_clause_style', 'top'), this will do nothing.
     * @param string $connection_name Which connection to use
     */
    public static function setupLimitClauseStyle($connection_name)
    {
        if (is_null(self::$config[$connection_name]['limit_clause_style'])) {
            self::$config[$connection_name]['limit_clause_style'] =
                self::detectLimitClauseStyle($connection_name);
        }
    }

    /**
     * Return the correct character used to quote identifiers (table
     * names, column names etc) by looking at the driver being used by PDO.
     * @param string $connection_name Which connection to use
     * @return string
     */
    protected static function detectIdentifierQuoteCharacter($connection_name)
    {
        switch (self::getConnection($connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case 'pgsql':
            case 'sqlsrv':
            case 'dblib':
            case 'mssql':
            case 'sybase':
            case 'firebird':
                return '"';
            case 'mysql':
            case 'sqlite':
            case 'sqlite2':
            default:
                return '`';
        }
    }

    /**
     * Returns a constant after determining the appropriate limit clause
     * style
     * @param string $connection_name Which connection to use
     * @return string Limit clause style keyword/constant
     */
    protected static function detectLimitClauseStyle($connection_name)
    {
        switch (self::getConnection($connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case 'sqlsrv':
            case 'dblib':
            case 'mssql':
                return ORM::LIMIT_STYLE_TOP_N;
            default:
                return ORM::LIMIT_STYLE_LIMIT;
        }
    }

    /**
     * Returns the PDO instance used by the the ORM to communicate with
     * the database. This can be called if any low-level Connection access is
     * required outside the class. If multiple connections are used,
     * accepts an optional key name for the connection.
     * @param string $connection_name Which connection to use
     * @return PDO
     */
    public static function getConnection($connection_name = self::DEFAULT_CONNECTION)
    {
        self::setupConnection($connection_name); // required in case this is called before Idiorm is instantiated
        return self::$connections[$connection_name];
    }

    /**
     * Executes a raw query as a wrapper for PDOStatement::execute.
     * Useful for queries that can't be accomplished through Idiorm,
     * particularly those using engine-specific features.
     * @example rawExecute('SELECT `name`, AVG(`order`) FROM `customer` GROUP BY `name` HAVING AVG(`order`) > 10')
     * @example rawExecute('INSERT OR REPLACE INTO `widget` (`id`, `name`) SELECT `id`, `name` FROM `other_table`')
     * @param string $query The raw SQL query
     * @param array $parameters Optional bound parameters
     * @param string $connection_name Which connection to use
     * @return bool Success
     */
    public static function rawExecute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION)
    {
        self::setupConnection($connection_name);
        return self::execute($query, $parameters, $connection_name);
    }

    /**
     * Returns the PDOStatement instance last used by any connection wrapped by the ORM.
     * Useful for access to PDOStatement::rowCount() or error information
     * @return PDOStatement
     */
    public static function getLastStatement()
    {
        return self::$last_statement;
    }

    /**
     * Internal helper method for executing statments. Logs queries, and
     * stores statement object in ::last_statment, accessible publicly
     * through ::getLastStatement()
     * @param string $query
     * @param array $parameters An array of parameters to be bound in to the query
     * @param string $connection_name Which connection to use
     * @return bool Response of PDOStatement::execute()
     */
    protected static function execute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION)
    {
        self::logQuery($query, $parameters, $connection_name);
        $statement = self::getConnection($connection_name)->prepare($query);

        self::$last_statement = $statement;

        return $statement->execute($parameters);
    }

    /**
     * Add a query to the internal query log. Only works if the
     * 'logging' config option is set to true.
     *
     * This works by manually binding the parameters to the query - the
     * query isn't executed like this (PDO normally passes the query and
     * parameters to the database which takes care of the binding) but
     * doing it this way makes the logged queries more readable.
     * @param string $query
     * @param array $parameters An array of parameters to be bound in to the query
     * @param string $connection_name Which connection to use
     * @return bool
     */
    protected static function logQuery($query, $parameters, $connection_name)
    {
        // If logging is not enabled, do nothing
        if (!self::$config[$connection_name]['logging']) {
            return false;
        }

        if (!isset(self::$query_log[$connection_name])) {
            self::$query_log[$connection_name] = array();
        }

        if (count($parameters) > 0) {
            // Escape the parameters
            $parameters = array_map(array(self::$connections[$connection_name], 'quote'), $parameters);

            // Avoid %format collision for vsprintf
            $query = str_replace("%", "%%", $query);

            // Replace placeholders in the query for vsprintf
            if (false !== strpos($query, "'") || false !== strpos($query, '"')) {
                $r = '/\G((?:(?:[^\x5C"\']|\x5C(?!["\'])|\x5C["\'])*?(?:\'(?:[^\x5C\']|\x5C(?!\')' .
                    '|\x5C\')*\')*(?:"(?:[^\x5C"]|\x5C(?!")|\x5C")*")*)*?)\?/';
                $query = preg_match($r, $query) ? preg_replace($r, "$1?", $query) : $query;
            } else {
                $query = str_replace("?", "%s", $query);
            }

            // Replace the question marks in the query with the parameters
            $bound_query = vsprintf($query, $parameters);
        } else {
            $bound_query = $query;
        }

        self::$last_query = $bound_query;
        self::$query_log[$connection_name][] = $bound_query;


        if (is_callable(self::$config[$connection_name]['logger'])) {
            $logger = self::$config[$connection_name]['logger'];
            $logger($bound_query);
        }

        return true;
    }

    /**
     * Get the last query executed. Only works if the
     * 'logging' config option is set to true. Otherwise
     * this will return null. Returns last query from all connections if
     * no connection_name is specified
     * @param null|string $connection_name Which connection to use
     * @return string
     */
    public static function getLastQuery($connection_name = null)
    {
        if ($connection_name === null) {
            return self::$last_query;
        }
        if (!isset(self::$query_log[$connection_name])) {
            return '';
        }

        return end(self::$query_log[$connection_name]);
    }

    /**
     * Get an array containing all the queries run on a
     * specified connection up to now.
     * Only works if the 'logging' config option is
     * set to true. Otherwise, returned array will be empty.
     * @param string $connection_name Which connection to use
     */
    public static function getQueryLog($connection_name = self::DEFAULT_CONNECTION)
    {
        if (isset(self::$query_log[$connection_name])) {
            return self::$query_log[$connection_name];
        }
        return array();
    }

    /**
     * Get a list of the available connection names
     * @return array
     */
    public static function getConnectionNames()
    {
        return array_keys(self::$connections);
    }

    // ------------------------ //
    // --- INSTANCE METHODS --- //
    // ------------------------ //

    /**
     * "Private" constructor; shouldn't be called directly.
     * Use the ORM::forTable factory method instead.
     */
    protected function __construct($table_name, $data = array(), $connection_name = self::DEFAULT_CONNECTION)
    {
		$this->table_name = $table_name;
		$this->data = $data;

		$this->connection_name = $connection_name;

		// Set the flag as config dictates
		$this->associative_results = self::$config[$this->connection_name]['find_many_primary_id_as_key'];

		self::setupConnectionConfig($connection_name);
    }

    /**
     * Create a new, empty instance of the class. Used
     * to add a new row to your database. May optionally
     * be passed an associative array of data to populate
     * the instance. If so, all fields will be flagged as
     * dirty so all will be saved to the database when
     * save() is called.
     */
    public function create($data = null)
    {
        $this->is_new = true;
        if (!is_null($data)) {
            return $this->hydrate($data)->forceAllDirty();
        }
        return $this;
    }

    /**
     * Set the ORM instance to return non associative results sets
     * @return ORM instance
     */
    public function nonAssociative()
    {
        $this->associative_results = false;
        return $this;
    }

    /**
     * Set the ORM instance to return associative results sets
     * @return ORM instance
     */
    public function associative()
    {
        $this->associative_results = true;
        return $this;
    }

    /**
     * Set the ORM instance to return associative (or not) results sets, as config dictates
     * @return ORM instance
     */
    public function resetAssociative()
    {
        $this->associative_results = self::$config[$this->connection_name]['find_many_primary_id_as_key'];
        return $this;
    }

    /**
     * Specify the ID column to use for this instance or array of instances only.
     * This overrides the id_column and id_column_overrides settings.
     *
     * This is mostly useful for libraries built on top of Idiorm, and will
     * not normally be used in manually built queries. If you don't know why
     * you would want to use this, you should probably just ignore it.
     */
    public function useIdColumn($id_column)
    {
        $this->instance_id_column = $id_column;
        return $this;
    }

    /**
     * Create an ORM instance from the given row (an associative
     * array of data fetched from the database)
     */
    protected function createInstanceFromRow($row)
    {
        $instance = static::forTable($this->table_name, $this->connection_name);
        $instance->useIdColumn($this->instance_id_column);
        $instance->hydrate($row);
        return $instance;
    }

    /**
     * Tell the ORM that you are expecting a single result
     * back from your query, and execute it. Will return
     * a single instance of the ORM class, or false if no
     * rows were returned.
     * As a shortcut, you may supply an ID as a parameter
     * to this method. This will perform a primary key
     * lookup on the table.
     */
    public function findOne($id = null)
    {
        if (!is_null($id)) {
            $this->whereIdIs($id);
        }

        $rows = $this->limit(1)->run();

        if (empty($rows)) {
            return false;
        }

        return $this->createInstanceFromRow($rows[0]);
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
        if (self::$config[$this->connection_name]['return_result_sets']) {
            return $this->findResultSet();
        }
        return $this->getInstances($this->run());
    }

    /**
     * Create instances of each row in the result and map
     * them to an associative array with the primary IDs as
     * the array keys.
     * @param array $rows
     * @return array
     */
    protected function getInstances($rows)
    {
        $size = count($rows);
        $instances = array();
        for ($i = 0; $i < $size; $i++) {
            $row = $this->createInstanceFromRow($rows[$i]);
            $key = (isset($row->{$this->instance_id_column}) && $this->associative_results) ? $row->id() : $i;
            $instances[$key] = $row;
        }
        return $instances;
    }

    /**
     * Tell the ORM that you are expecting multiple results
     * from your query, and execute it. Will return a result set object
     * containing instances of the ORM class.
     * @return ResultSet
     */
    public function findResultSet()
    {
        $resultSetClass = $this->resultSetClass;
        if (is_a($resultSetClass, __NAMESPACE__ . '\\ResultSet', true)) {
            $result = new $resultSetClass($this->getInstances($this->run()));
        } else {
            $result = new ResultSet($this->getInstances($this->run()));
        }
        return $result;
    }

    /**
     * Tell the ORM that you are expecting multiple results
     * from your query, and execute it. Will return an array,
     * or an empty array if no rows were returned.
     * @return array
     */
    public function findArray()
    {
        return $this->run();
    }

    /**
     * Tell the ORM that you wish to execute a COUNT query.
     * Will return an integer representing the number of
     * rows returned.
     */
    public function count($column = '*')
    {
        return $this->callAggregateDBFunction(__FUNCTION__, $column);
    }

    /**
     * Tell the ORM that you wish to execute a MAX query.
     * Will return the max value of the choosen column.
     */
    public function max($column)
    {
        return $this->callAggregateDBFunction(__FUNCTION__, $column);
    }

    /**
     * Tell the ORM that you wish to execute a MIN query.
     * Will return the min value of the choosen column.
     */
    public function min($column)
    {
        return $this->callAggregateDBFunction(__FUNCTION__, $column);
    }

    /**
     * Tell the ORM that you wish to execute a AVG query.
     * Will return the average value of the choosen column.
     */
    public function avg($column)
    {
        return $this->callAggregateDBFunction(__FUNCTION__, $column);
    }

    /**
     * Tell the ORM that you wish to execute a SUM query.
     * Will return the sum of the choosen column.
     */
    public function sum($column)
    {
        return $this->callAggregateDBFunction(__FUNCTION__, $column);
    }

    /**
     * Execute an aggregate query on the current connection.
     * @param string $sql_function The aggregate function to call eg. MIN, COUNT, etc
     * @param string $column The column to execute the aggregate query against
     * @return int
     */
    protected function callAggregateDBFunction($sql_function, $column)
    {
        $alias = strtolower($sql_function);
        $sql_function = strtoupper($sql_function);
        if ('*' != $column) {
            $column = $this->quoteIdentifier($column);
        }
        $result_columns = $this->result_columns;
        $this->result_columns = array();
        $this->selectExpr("$sql_function($column)", $alias);
        $result = $this->findOne();
        $this->result_columns = $result_columns;

        $return_value = 0;
        if ($result !== false && isset($result->$alias)) {
            if (!is_numeric($result->$alias)) {
                $return_value = $result->$alias;
            } elseif ((int)$result->$alias == (float)$result->$alias) {
                $return_value = (int)$result->$alias;
            } else {
                $return_value = (float)$result->$alias;
            }
        }
        return $return_value;
    }

    /**
     * This method can be called to hydrate (populate) this
     * instance of the class from an associative array of data.
     * This will usually be called only from inside the class,
     * but it's public in case you need to call it directly.
     */
    public function hydrate($data = array())
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Force the ORM to flag all the fields in the $data array
     * as "dirty" and therefore update them when save() is called.
     */
    public function forceAllDirty()
    {
        $this->dirty_fields = $this->data;
        return $this;
    }

    /**
     * Perform a raw query. The query can contain placeholders in
     * either named or question mark style. If placeholders are
     * used, the parameters should be an array of values which will
     * be bound to the placeholders in the query. If this method
     * is called, all other query building methods will be ignored.
     */
    public function rawQuery($query, $parameters = array())
    {
        $this->is_raw_query = true;
        $this->raw_query = $query;
        $this->raw_parameters = $parameters;
        return $this;
    }

    /**
     * Add an alias for the main table to be used in SELECT queries
     */
    public function tableAlias($alias)
    {
        $this->table_alias = $alias;
        return $this;
    }

    /**
     * Internal method to add an unquoted expression to the set
     * of columns returned by the SELECT query. The second optional
     * argument is the alias to return the expression as.
     */
    protected function addResultColumn($expr, $alias = null)
    {
        if (!is_null($alias)) {
            $expr .= " AS " . $this->quoteIdentifier($alias);
        }

        if ($this->using_default_result_columns) {
            $this->result_columns = array($expr);
            $this->using_default_result_columns = false;
        } else {
            $this->result_columns[] = $expr;
        }
        return $this;
    }

    /**
     * Add a column to the list of columns returned by the SELECT
     * query. This defaults to '*'. The second optional argument is
     * the alias to return the column as.
     * @return ORM
     */
    public function select($column, $alias = null)
    {
        $columns = array_map('trim', explode(',', $column));
        foreach ($columns as $column) {
            $column = $this->quoteIdentifier($column);
            $this->addResultColumn($column, $alias);
        }
        return $this;
    }

    /**
     * Add an unquoted expression to the list of columns returned
     * by the SELECT query. The second optional argument is
     * the alias to return the column as.
     */
    public function selectExpr($expr, $alias = null)
    {
        return $this->addResultColumn($expr, $alias);
    }

    /**
     * Add columns to the list of columns returned by the SELECT
     * query. This defaults to '*'. Many columns can be supplied
     * as either an array or as a list of parameters to the method.
     *
     * Note that the alias must not be numeric - if you want a
     * numeric alias then prepend it with some alpha chars. eg. a1
     *
     * @example selectMany(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5');
     * @example selectMany('column', 'column2', 'column3');
     * @example selectMany(array('column', 'column2', 'column3'), 'column4', 'column5');
     *
     * @return ORM
     */
    public function selectMany()
    {
        $columns = func_get_args();
        if (!empty($columns)) {
            $columns = $this->normaliseSelectManyColumns($columns);
            foreach ($columns as $alias => $column) {
                if (is_numeric($alias)) {
                    $alias = null;
                }
                $this->select($column, $alias);
            }
        }
        return $this;
    }

    /**
     * Add an unquoted expression to the list of columns returned
     * by the SELECT query. Many columns can be supplied as either
     * an array or as a list of parameters to the method.
     *
     * Note that the alias must not be numeric - if you want a
     * numeric alias then prepend it with some alpha chars. eg. a1
     *
     * @example selectManyExpr(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5')
     * @example selectManyExpr('column', 'column2', 'column3')
     * @example selectManyExpr(array('column', 'column2', 'column3'), 'column4', 'column5')
     *
     * @return ORM
     */
    public function selectManyExpr()
    {
        $columns = func_get_args();
        if (!empty($columns)) {
            $columns = $this->normaliseSelectManyColumns($columns);
            foreach ($columns as $alias => $column) {
                if (is_numeric($alias)) {
                    $alias = null;
                }
                $this->selectExpr($column, $alias);
            }
        }
        return $this;
    }

    /**
     * Take a column specification for the select many methods and convert it
     * into a normalised array of columns and aliases.
     *
     * It is designed to turn the following styles into a normalised array:
     *
     * array(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5'))
     *
     * @param array $columns
     * @return array
     */
    protected function normaliseSelectManyColumns($columns)
    {
        $return = array();
        foreach ($columns as $column) {
            if (is_array($column)) {
                foreach ($column as $key => $value) {
                    if (!is_numeric($key)) {
                        $return[$key] = $value;
                    } else {
                        $return[] = $value;
                    }
                }
            } else {
                $return[] = $column;
            }
        }
        return $return;
    }

    /**
     * Add a DISTINCT keyword before the list of columns in the SELECT query
     */
    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    /**
     * Internal method to add a JOIN source to the query.
     *
     * The join_operator should be one of INNER, LEFT OUTER, CROSS etc - this
     * will be prepended to JOIN.
     *
     * The table should be the name of the table to join to.
     *
     * The constraint may be either a string or an array with three elements. If it
     * is a string, it will be compiled into the query as-is, with no escaping. The
     * recommended way to supply the constraint is as an array with three elements:
     *
     * first_column, operator, second_column
     *
     * Example: array('user.id', '=', 'profile.user_id')
     *
     * will compile to
     *
     * ON `user`.`id` = `profile`.`user_id`
     *
     * The final (optional) argument specifies an alias for the joined table.
     */
    protected function addJoinSource($join_operator, $table, $constraint, $table_alias = null)
    {

        $join_operator = trim("{$join_operator} JOIN");

        $table = $this->quoteIdentifier($table);

        // Add table alias if present
        if (!is_null($table_alias)) {
            $table_alias = $this->quoteIdentifier($table_alias);
            $table .= " {$table_alias}";
        }

        // Build the constraint
        if (is_array($constraint)) {
            list($first_column, $operator, $second_column) = $constraint;
            $first_column = $this->quoteIdentifier($first_column);
            $second_column = $this->quoteIdentifier($second_column);
            $constraint = "{$first_column} {$operator} {$second_column}";
        }

        $this->join_sources[] = "{$join_operator} {$table} ON {$constraint}";
        return $this;
    }

    /**
     * Add a simple JOIN source to the query
     */
    public function join($table, $constraint, $table_alias = null)
    {
        return $this->addJoinSource("", $table, $constraint, $table_alias);
    }

    /**
     * Add an INNER JOIN souce to the query
     */
    public function innerJoin($table, $constraint, $table_alias = null)
    {
        return $this->addJoinSource("INNER", $table, $constraint, $table_alias);
    }

    /**
     * Add a LEFT OUTER JOIN souce to the query
     */
    public function leftOuterJoin($table, $constraint, $table_alias = null)
    {
        return $this->addJoinSource("LEFT OUTER", $table, $constraint, $table_alias);
    }

    /**
     * Add an RIGHT OUTER JOIN souce to the query
     */
    public function rightOuterJoin($table, $constraint, $table_alias = null)
    {
        return $this->addJoinSource("RIGHT OUTER", $table, $constraint, $table_alias);
    }

    /**
     * Add an FULL OUTER JOIN souce to the query
     */
    public function fullOuterJoin($table, $constraint, $table_alias = null)
    {
        return $this->addJoinSource("FULL OUTER", $table, $constraint, $table_alias);
    }

    /**
     * Internal method to add a HAVING condition to the query
     */
    protected function addHaving($fragment, $values = array())
    {
        return $this->addCondition('having', $fragment, $values);
    }

    /**
     * Internal method to add a HAVING condition to the query
     */
    protected function addSimpleHaving($column_name, $separator, $value)
    {
        return $this->addSimpleCondition('having', $column_name, $separator, $value);
    }

    /**
     * Internal method to add a WHERE condition to the query
     */
    protected function addWhere($fragment, $values = array())
    {
        return $this->addCondition('where', $fragment, $values);
    }

    /**
     * Internal method to add a WHERE condition to the query
     */
    protected function addSimpleWhere($column_name, $separator, $value)
    {
        return $this->addSimpleCondition('where', $column_name, $separator, $value);
    }

    /**
     * Internal method to add a HAVING or WHERE condition to the query
     */
    protected function addCondition($type, $fragment, $values = array())
    {
        $conditions_class_property_name = "{$type}_conditions";
        if (!is_array($values)) {
            $values = array($values);
        }
        array_push($this->$conditions_class_property_name, array(
            self::CONDITION_FRAGMENT => $fragment,
            self::CONDITION_VALUES => $values,
        ));
        return $this;
    }

    /**
     * Helper method to compile a simple COLUMN SEPARATOR VALUE
     * style HAVING or WHERE condition into a string and value ready to
     * be passed to the _add_condition method. Avoids duplication
     * of the call to _quote_identifier
     */
    protected function addSimpleCondition($type, $column_name, $separator, $value)
    {
        // Add the table name in case of ambiguous columns
     /*   if (count($this->join_sources) > 0 && strpos($column_name, '.') === false) {
            $table = $this->table_name;
            if (!is_null($this->table_alias)) {
                $table = $this->table_alias;
            }

            $column_name = "{$table}.{$column_name}";
        }
        $column_name = $this->quoteIdentifier($column_name);
        return $this->addCondition($type, "{$column_name} {$separator} ?", $value);*/
        $multiple = is_array($column_name) ? $column_name : array($column_name => $value);
        $result = $this;
        foreach($multiple as $key => $val) {
            // Add the table name in case of ambiguous columns
            if (count($result->join_sources) > 0 && strpos($key, '.') === false) {
                $table = $result->table_name;
                if (!is_null($result->table_alias)) {
                    $table = $result->table_alias;
                }
                $key = "{$table}.{$key}";
            }
            $key = $result->quoteIdentifier($key);
            $result = $result->addCondition($type, "{$key} {$separator} ?", $val);
        }
        return $result;
    }

    /**
     * Return a string containing the given number of question marks,
     * separated by commas. Eg "?, ?, ?"
     */
    protected function createPlaceholders($fields)
    {
        if (!empty($fields)) {
            $connection_fields = array();
            foreach ($fields as $key => $value) {
                // Process expression fields directly into the query
                if (array_key_exists($key, $this->expr_fields)) {
                    $connection_fields[] = $value;
                } else {
                    $connection_fields[] = '?';
                }
            }
            return implode(', ', $connection_fields);
        }
    }

    /**
     * Add a WHERE column = value clause to your query. Each time
     * this is called in the chain, an additional WHERE will be
     * added, and these will be ANDed together when the final query
     * is built.
     */
    public function where($column_name, $value = null)
    {
        return $this->whereEqual($column_name, $value);
    }

    /**
     * More explicitly named version of for the where() method.
     * Can be used if preferred.
     */
    public function whereEqual($column_name, $value = null)
    {
        return $this->addSimpleWhere($column_name, '=', $value);
    }

    /**
     * Add a WHERE column != value clause to your query.
     */
    public function whereNotEqual($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, '!=', $value);
    }

    /**
     * Special method to query the table by its primary key
     */
    public function whereIdIs($id)
    {
        return $this->where($this->getIdColumnName(), $id);
    }

    /**
     * Add a WHERE ... LIKE clause to your query.
     */
    public function whereLike($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, 'LIKE', $value);
    }

    /**
     * Add where WHERE ... NOT LIKE clause to your query.
     */
    public function whereNotLike($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, 'NOT LIKE', $value);
    }

    /**
     * Add a WHERE ... > clause to your query
     */
    public function whereGt($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, '>', $value);
    }

    /**
     * Add a WHERE ... < clause to your query
     */
    public function whereLt($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, '<', $value);
    }

    /**
     * Add a WHERE ... >= clause to your query
     */
    public function whereGte($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, '>=', $value);
    }

    /**
     * Add a WHERE ... <= clause to your query
     */
    public function whereLte($column_name, $value)
    {
        return $this->addSimpleWhere($column_name, '<=', $value);
    }

    /**
     * Add a WHERE ... IN clause to your query
     * @param string $column_name
     * @param array $values
     * @return $this|ORM
     */
    public function whereIn($column_name, $values)
    {
        return $this->addWherePlaceholder($column_name, 'IN', $values);
    }

    /**
     * Add a WHERE ... NOT IN clause to your query
     * @param string $column_name
     * @param array $values
     * @return $this|ORM
     */
    public function whereNotIn($column_name, $values)
    {
        return $this->addWherePlaceholder($column_name, 'NOT IN', $values);
    }

    /**
     * Add a WHERE column IS NULL clause to your query
     */
    public function whereNull($column_name)
    {
        $column_name = $this->quoteIdentifier($column_name);
        return $this->addWhere("{$column_name} IS NULL");
    }

    /**
     * Add a WHERE column IS NOT NULL clause to your query
     */
    public function whereNotNull($column_name)
    {
        $column_name = $this->quoteIdentifier($column_name);
        return $this->addWhere("{$column_name} IS NOT NULL");
    }

    /**
     * Add a raw WHERE clause to the query. The clause should
     * contain question mark placeholders, which will be bound
     * to the parameters supplied in the second argument.
     */
    public function rawWhere($clause, $parameters = array())
    {
        return $this->addWhere($clause, $parameters);
    }

    /**
     * Add a LIMIT to the query
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Add an OFFSET to the query
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Add an ORDER BY clause to the query
     */
    protected function addOrderBy($column_name, $ordering)
    {
        $column_name = $this->quoteIdentifier($column_name);
        $this->order_by[] = "{$column_name} {$ordering}";
        return $this;
    }
    /**
     * Add a WHERE clause with multiple values (like IN and NOT IN)
     */
    protected function addWherePlaceholder($column_name, $separator, $values) {
        if (!is_array($column_name)) {
            $data = array($column_name => $values);
        } else {
            $data = $column_name;
        }
        $result = $this;
        foreach ($data as $key => $val) {
            $column = $result->quoteIdentifier($key);
            $placeholders = $result->createPlaceholders($val);
            $result = $result->addWhere("{$column} {$separator} ({$placeholders})", $val);
        }
        return $result;
    }
    /**
     * Add an ORDER BY column DESC clause
     */
    public function orderByDesc($column_name)
    {
        return $this->addOrderBy($column_name, 'DESC');
    }

    /**
     * Add an ORDER BY column ASC clause
     */
    public function orderByAsc($column_name)
    {
        return $this->addOrderBy($column_name, 'ASC');
    }

    /**
     * Add an unquoted expression as an ORDER BY clause
     */
    public function orderByExpr($clause)
    {
        $this->order_by[] = $clause;
        return $this;
    }

    /**
     * Add a column to the list of columns to GROUP BY
     */
    public function groupBy($column_name)
    {
        $column_name = $this->quoteIdentifier($column_name);
        $this->group_by[] = $column_name;
        return $this;
    }

    /**
     * Add an unquoted expression to the list of columns to GROUP BY
     */
    public function groupByExpr($expr)
    {
        $this->group_by[] = $expr;
        return $this;
    }

    /**
     * Add a HAVING column = value clause to your query. Each time
     * this is called in the chain, an additional HAVING will be
     * added, and these will be ANDed together when the final query
     * is built.
     */
    public function having($column_name, $value)
    {
        return $this->havingEqual($column_name, $value);
    }

    /**
     * More explicitly named version of for the having() method.
     * Can be used if preferred.
     */
    public function havingEqual($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '=', $value);
    }

    /**
     * Add a HAVING column != value clause to your query.
     */
    public function havingNotEqual($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '!=', $value);
    }

    /**
     * Special method to query the table by its primary key
     */
    public function havingIdIs($id)
    {
        return $this->having($this->getIdColumnName(), $id);
    }

    /**
     * Add a HAVING ... LIKE clause to your query.
     */
    public function havingLike($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, 'LIKE', $value);
    }

    /**
     * Add where HAVING ... NOT LIKE clause to your query.
     */
    public function havingNotLike($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, 'NOT LIKE', $value);
    }

    /**
     * Add a HAVING ... > clause to your query
     */
    public function havingGt($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '>', $value);
    }

    /**
     * Add a HAVING ... < clause to your query
     */
    public function havingLt($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '<', $value);
    }

    /**
     * Add a HAVING ... >= clause to your query
     */
    public function havingGte($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '>=', $value);
    }

    /**
     * Add a HAVING ... <= clause to your query
     */
    public function havingLte($column_name, $value)
    {
        return $this->addSimpleHaving($column_name, '<=', $value);
    }

    /**
     * Add a HAVING ... IN clause to your query
     */
    public function havingIn($column_name, $values)
    {
        $column_name = $this->quoteIdentifier($column_name);
        $placeholders = $this->createPlaceholders($values);
        return $this->addHaving("{$column_name} IN ({$placeholders})", $values);
    }

    /**
     * Add a HAVING ... NOT IN clause to your query
     */
    public function havingNotIn($column_name, $values)
    {
        $column_name = $this->quoteIdentifier($column_name);
        $placeholders = $this->createPlaceholders($values);
        return $this->addHaving("{$column_name} NOT IN ({$placeholders})", $values);
    }

    /**
     * Add a HAVING column IS NULL clause to your query
     */
    public function havingNull($column_name)
    {
        $column_name = $this->quoteIdentifier($column_name);
        return $this->addHaving("{$column_name} IS NULL");
    }

    /**
     * Add a HAVING column IS NOT NULL clause to your query
     */
    public function havingNotNull($column_name)
    {
        $column_name = $this->quoteIdentifier($column_name);
        return $this->addHaving("{$column_name} IS NOT NULL");
    }

    /**
     * Add a raw HAVING clause to the query. The clause should
     * contain question mark placeholders, which will be bound
     * to the parameters supplied in the second argument.
     */
    public function rawHaving($clause, $parameters = array())
    {
        return $this->addHaving($clause, $parameters);
    }

    /**
     * Build a SELECT statement based on the clauses that have
     * been passed to this instance by chaining method calls.
     */
    protected function buildSelect()
    {
        // If the query is raw, just set the $this->values to be
        // the raw query parameters and return the raw query
        if ($this->is_raw_query) {
            $this->values = $this->raw_parameters;
            return $this->raw_query;
        }

        // Build and return the full SELECT statement by concatenating
        // the results of calling each separate builder method.
        return $this->joinIfNotEmpty(" ", array(
            $this->buildSelectStart(),
            $this->buildJoin(),
            $this->buildWhere(),
            $this->buildGroupBy(),
            $this->buildHaving(),
            $this->buildOrderBy(),
            $this->buildLimit(),
            $this->buildOffset(),
        ));
    }

    /**
     * Build the start of the SELECT statement
     */
    protected function buildSelectStart()
    {
        $fragment = 'SELECT ';
        $result_columns = join(', ', $this->result_columns);

        if (!is_null($this->limit) &&
            self::$config[$this->connection_name]['limit_clause_style'] === ORM::LIMIT_STYLE_TOP_N
        ) {
            $fragment .= "TOP {$this->limit} ";
        }

        if ($this->distinct) {
            $result_columns = 'DISTINCT ' . $result_columns;
        }

        $fragment .= "{$result_columns} FROM " . $this->quoteIdentifier($this->table_name);

        if (!is_null($this->table_alias)) {
            $fragment .= " " . $this->quoteIdentifier($this->table_alias);
        }
        return $fragment;
    }

    /**
     * Build the JOIN sources
     */
    protected function buildJoin()
    {
        if (count($this->join_sources) === 0) {
            return '';
        }

        return join(" ", $this->join_sources);
    }

    /**
     * Build the WHERE clause(s)
     */
    protected function buildWhere()
    {
        return $this->buildConditions('where');
    }

    /**
     * Build the HAVING clause(s)
     */
    protected function buildHaving()
    {
        return $this->buildConditions('having');
    }

    /**
     * Build GROUP BY
     */
    protected function buildGroupBy()
    {
        if (count($this->group_by) === 0) {
            return '';
        }
        return "GROUP BY " . join(", ", $this->group_by);
    }

    /**
     * Build a WHERE or HAVING clause
     * @param string $type
     * @return string
     */
    protected function buildConditions($type)
    {
        $conditions_class_property_name = "{$type}_conditions";
        // If there are no clauses, return empty string
        if (count($this->$conditions_class_property_name) === 0) {
            return '';
        }

        $conditions = array();
        foreach ($this->$conditions_class_property_name as $condition) {
            $conditions[] = $condition[self::CONDITION_FRAGMENT];
            $this->values = array_merge($this->values, $condition[self::CONDITION_VALUES]);
        }

        return strtoupper($type) . " " . join(" AND ", $conditions);
    }

    /**
     * Build ORDER BY
     */
    protected function buildOrderBy()
    {
        if (count($this->order_by) === 0) {
            return '';
        }
        return "ORDER BY " . join(", ", $this->order_by);
    }

    /**
     * Build LIMIT
     */
    protected function buildLimit()
    {
        $fragment = '';
        if (!is_null($this->limit) &&
            self::$config[$this->connection_name]['limit_clause_style'] == ORM::LIMIT_STYLE_LIMIT
        ) {
            if (self::getConnection($this->connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME) == 'firebird') {
                $fragment = 'ROWS';
            } else {
                $fragment = 'LIMIT';
            }
            $fragment .= " {$this->limit}";
        }
        return $fragment;
    }

    /**
     * Build OFFSET
     */
    protected function buildOffset()
    {
        if (!is_null($this->offset)) {
            $clause = 'OFFSET';
            if (self::getConnection($this->connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME) == 'firebird') {
                $clause = 'TO';
            }
            return "$clause " . $this->offset;
        }
        return '';
    }

    /**
     * Wrapper around PHP's join function which
     * only adds the pieces if they are not empty.
     */
    protected function joinIfNotEmpty($glue, $pieces)
    {
        $filtered_pieces = array();
        foreach ($pieces as $piece) {
            if (is_string($piece)) {
                $piece = trim($piece);
            }
            if (!empty($piece)) {
                $filtered_pieces[] = $piece;
            }
        }
        return join($glue, $filtered_pieces);
    }

    /**
     * Quote a string that is used as an identifier
     * (table names, column names etc). This method can
     * also deal with dot-separated identifiers eg table.column
     */
    protected function quoteIdentifier($identifier)
    {
        $parts = explode('.', $identifier);
        $parts = array_map(array($this, 'quoteIdentifierPart'), $parts);
        return join('.', $parts);
    }

    /**
     * This method performs the actual quoting of a single
     * part of an identifier, using the identifier quote
     * character specified in the config (or autodetected).
     */
    protected function quoteIdentifierPart($part)
    {
        if ($part === '*') {
            return $part;
        }

        $quote_character = self::$config[$this->connection_name]['identifier_quote_character'];
        // double up any identifier quotes to escape them
        return $quote_character .
        str_replace($quote_character,
            $quote_character . $quote_character,
            $part
        ) . $quote_character;
    }

    /**
     * Create a cache key for the given query and parameters.
     */
    protected static function createCacheKey($query, $parameters)
    {
        $parameter_string = join(',', $parameters);
        $key = $query . ':' . $parameter_string;
        return sha1($key);
    }

    /**
     * Check the query cache for the given cache key. If a value
     * is cached for the key, return the value. Otherwise, return false.
     */
    protected static function checkQueryCache($cache_key, $connection_name = self::DEFAULT_CONNECTION)
    {
        if (isset(self::$query_cache[$connection_name][$cache_key])) {
            return self::$query_cache[$connection_name][$cache_key];
        }
        return false;
    }

    /**
     * Clear the query cache
     */
    public static function clearCache()
    {
        self::$query_cache = array();
    }

    /**
     * Add the given value to the query cache.
     */
    protected static function cacheQueryResult($cache_key, $value, $connection_name = self::DEFAULT_CONNECTION)
    {
        if (!isset(self::$query_cache[$connection_name])) {
            self::$query_cache[$connection_name] = array();
        }
        self::$query_cache[$connection_name][$cache_key] = $value;
    }

    /**
     * Execute the SELECT query that has been built up by chaining methods
     * on this class. Return an array of rows as associative arrays.
     */
    protected function run()
    {
        $query = $this->buildSelect();
        $caching_enabled = self::$config[$this->connection_name]['caching'];

        if ($caching_enabled) {
            $cache_key = self::createCacheKey($query, $this->values);
            $cached_result = self::checkQueryCache($cache_key, $this->connection_name);

            if ($cached_result !== false) {
                $this->reset();
                return $cached_result;
            }
        }

        self::execute($query, $this->values, $this->connection_name);
        $statement = self::getLastStatement();

        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        if ($caching_enabled) {
            self::cacheQueryResult($cache_key, $rows, $this->connection_name);
        }

        // reset Idiorm after executing the query
        $this->reset();

        return $rows;
    }


    /**
     * reset Idiorm after executing the query
     */
    protected function reset()
    {
        $this->values = array();
        $this->result_columns = array('*');
        $this->using_default_result_columns = true;
    }


    /**
     * Return the raw data wrapped by this ORM
     * instance as an associative array. Column
     * names may optionally be supplied as arguments,
     * if so, only those keys will be returned.
     */
    public function asArray()
    {
        if (func_num_args() === 0) {
            return $this->data;
        }
        $args = func_get_args();
        return array_intersect_key($this->data, array_flip($args));
    }

    /**
     * Return the value of a property of this object (database row)
     * or null if not present.
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Return the name of the column in the database table which contains
     * the primary key ID of the row.
     */
    protected function getIdColumnName()
    {
        if (!is_null($this->instance_id_column)) {
            return $this->instance_id_column;
        }
        if (isset(self::$config[$this->connection_name]['id_column_overrides'][$this->table_name])) {
            return self::$config[$this->connection_name]['id_column_overrides'][$this->table_name];
        }
        return self::$config[$this->connection_name]['id_column'];
    }

    /**
     * Get the primary key ID of this object.
     */
    public function id()
    {
        return $this->get($this->getIdColumnName());
    }

    /**
     * Set a property to a particular value on this object.
     * To set multiple properties at once, pass an associative array
     * as the first parameter and leave out the second parameter.
     * Flags the properties as 'dirty' so they will be saved to the
     * database when save() is called.
     */
    public function set($key, $value = null)
    {
        return $this->setOrmProperty($key, $value);
    }

    /**
     * Set a property to a particular value on this object.
     * To set multiple properties at once, pass an associative array
     * as the first parameter and leave out the second parameter.
     * Flags the properties as 'dirty' so they will be saved to the
     * database when save() is called.
     * @param string|array $key
     * @param string|null $value
     */
    public function setExpr($key, $value = null)
    {
        return $this->setOrmProperty($key, $value, true);
    }

    /**
     * Set a property on the ORM object.
     * @param string|array $key
     * @param string|null $value
     * @param bool $raw Whether this value should be treated as raw or not
     * @return ORM
     */
    protected function setOrmProperty($key, $value = null, $raw = false)
    {
        if (!is_array($key)) {
            $key = array($key => $value);
        }
        foreach ($key as $field => $value) {
            $this->data[$field] = $value;
            $this->dirty_fields[$field] = $value;
            if (false === $raw and isset($this->expr_fields[$field])) {
                unset($this->expr_fields[$field]);
            } else if (true === $raw) {
                $this->expr_fields[$field] = true;
            }
        }
        return $this;
    }

    /**
     * Check whether the given field has been changed since this
     * object was saved.
     */
    public function isDirty($key)
    {
        return isset($this->dirty_fields[$key]);
    }

    /**
     * Check whether the model was the result of a call to create() or not
     * @return bool
     */
    public function isNew()
    {
        return $this->is_new;
    }

    /**
     * Save any fields which have been modified on this object
     * to the database.
     * Added: on duplicate key update, only for mysql
     * If you want to insert a record, or update it if any of the unique keys already exists on Connection
     */
    public function save($ignore = false)
    {

        // remove any expression fields as they are already baked into the query
        $values = array_values(array_diff_key($this->dirty_fields, $this->expr_fields));

        if ($ignore) {
            $query = $this->buildInsertUpdate();
            $values = array_merge($values, $values);
        } else {
            if (!$this->is_new) { // UPDATE
                // If there are no dirty values, do nothing
                if (empty($values) && empty($this->expr_fields)) {
                    return true;
                }
                $query = $this->buildUpdate();
                $values[] = $this->id();
            } else { // INSERT
                $query = $this->buildInsert();
            }
        }

        $success = self::execute($query, $values, $this->connection_name);

        // If we've just inserted a new record, set the ID of this object
        if ($this->is_new) {
            $this->is_new = false;
            if (is_null($this->id())) {
                if (self::getConnection($this->connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
                    $this->data[$this->getIdColumnName()] = self::getLastStatement()->fetchColumn();
                } else {
                    $this->data[$this->getIdColumnName()] = self::getConnection($this->connection_name)->lastInsertId();
                }
            }
        }
        $this->clearCache();
        $this->dirty_fields = $this->expr_fields = array();
        return $success;
    }

    /**
     * Build an UPDATE query
     */
    protected function buildUpdate()
    {
        $query = array();
        $query[] = "UPDATE {$this->quoteIdentifier($this->table_name)} SET";

        $field_list = array();
        foreach ($this->dirty_fields as $key => $value) {
            if (!array_key_exists($key, $this->expr_fields)) {
                $value = '?';
            }
            $field_list[] = "{$this->quoteIdentifier($key)} = $value";
        }
        $query[] = join(", ", $field_list);
        $query[] = "WHERE";
        $query[] = $this->quoteIdentifier($this->getIdColumnName());
        $query[] = "= ?";
        return join(" ", $query);
    }

    /**
     * Build an INSERT query
     */
    protected function buildInsert()
    {
        $query[] = "INSERT INTO";
        $query[] = $this->quoteIdentifier($this->table_name);
        $field_list = array_map(array($this, 'quoteIdentifier'), array_keys($this->dirty_fields));
        $query[] = "(" . join(", ", $field_list) . ")";
        $query[] = "VALUES";

        $placeholders = $this->createPlaceholders($this->dirty_fields);
        $query[] = "({$placeholders})";

        if (self::getConnection($this->connection_name)->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
            $query[] = 'RETURNING ' . $this->quoteIdentifier($this->getIdColumnName());
        }

        return join(" ", $query);
    }

    /**
     * Added: Build an INSERT ON DUPLICATE KEY UPDATE query
     * Attention: This method only works on Mysql Databases
     */
    protected function buildInsertUpdate()
    {
        $query = array();
        $query[] = "INSERT INTO";
        $query[] = $this->quoteIdentifier($this->table_name);
        $field_list = array_map(array($this, 'quoteIdentifier'), array_keys($this->dirty_fields));
        $query[] = "(" . implode(", ", $field_list) . ")";
        $query[] = "VALUES";
        $placeholders = $this->createPlaceholders($this->dirty_fields);
        $query[] = "({$placeholders})";

        $query[] = " ON DUPLICATE KEY UPDATE ";
        $query[] = implode(" = ?, ", $field_list) . " = ? ";
        return implode(" ", $query);
    }


    /**
     * Delete this record from the database
     */
    public function delete()
    {
        $query = join(" ", array(
            "DELETE FROM",
            $this->quoteIdentifier($this->table_name),
            "WHERE",
            $this->quoteIdentifier($this->getIdColumnName()),
            "= ?",
        ));

        return self::execute($query, array($this->id()), $this->connection_name);
    }

    /**
     * Delete many records from the database
     * Added: could delete many of a join query, if you define $join to true
     * and the table where you want to delete the records
     */
    public function deleteMany($join = false, $table = false)
    {
        if ($join) {
            // Build and return the full DELETE statement by concatenating
            // the results of calling each separate builder method.
            $query = $this->joinIfNotEmpty(" ", array(
                "DELETE $table FROM",
                $this->quoteIdentifier($this->table_name),
                $this->buildJoin(),
                $this->buildWhere()
            ));
        } else {
            // Build and return the full DELETE statement by concatenating
            // the results of calling each separate builder method.
            $query = $this->joinIfNotEmpty(" ", array(
                "DELETE FROM",
                $this->quoteIdentifier($this->table_name),
                $this->buildWhere(),
            ));
        }

        return self::execute($query, $this->values, $this->connection_name);
    }


    // --------------------- //
    // ---  ArrayAccess  --- //
    // --------------------- //

    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            throw new \InvalidArgumentException('You must specify a key/array index.');
        }
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        unset($this->data[$key]);
        unset($this->dirty_fields[$key]);
    }

    // --------------------- //
    // --- MAGIC METHODS --- //
    // --------------------- //
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /** Iterator methods **/

    function rewind() {
        return reset($this->data);
    }
    function current() {
        return current($this->data);
    }
    function key() {
        return key($this->data);
    }
    function next() {
        return next($this->data);
    }
    function valid() {
        return key($this->data) !== null;
    }
}

/**
 * A result set class for working with collections of model instances
 * @author Simon Holywell <treffynnon@php.net>
 */

class ResultSet implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * The current result set as an array
     * @var array
     */
    protected $results = array();

    /**
     * Optionally set the contents of the result set by passing in array
     * @param array $results
     */
    public function __construct(array $results = array())
    {
        $this->setResults($results);
    }

    /**
     * Set the contents of the result set by passing in array
     * @param array $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }

    /**
     * Get the current result set as an array
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get the current result set as an array
     * @return array
     */
    public function asArray()
    {
        return $this->getResults();
    }

    /**
     * Get the current result set as an array
     * @return array
     */
    public function asJson()
    {
        $result = array();
        foreach ($this->results as $key => $value) {
            /**
             * @var $value ORM
             */
            $result[] = $value->asArray();
        }
        return json_encode($result);
    }

    /**
     * Get the array keys (primary keys of the results)
     * @return array
     */
    public function keys()
    {
        return array_keys($this->results);
    }

    /**
     * Merge the resultSet with an array
     * @param ResultSet $result
     * @return $this
     */
    public function merge(ResultSet $result)
    {
        array_merge($this->results, $result->getResults());
        return $this;
    }

    /**
     * Get the first element of the result set
     * @return Model
     */
    public function first()
    {
        return reset($this->results);
    }

    /**
     * Get the last element of the result set
     * @return Model
     */
    public function last()
    {
        return end($this->results);
    }

    /**
     * Push an element on the result set
     * @return Model
     */
    public function add($value)
    {
        array_push($this->results, $value);
        return $this;
    }

    public function rewind()
    {
        return reset($this->results);
    }

    public function current()
    {
        return current($this->results);
    }

    public function key()
    {
        return key($this->results);
    }

    public function next()
    {
        return next($this->results);
    }

    public function valid()
    {
        return isset($this->results[$this->id()]);
    }

    /**
     * Get the number of records in the result set
     * @return int
     */
    public function count()
    {
        return count($this->results);
    }

    /**
     * Get an iterator for this object. In this case it supports foreaching
     * over the result set.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->results);
    }

    /**
     * ArrayAccess
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->results[$offset]);
    }

    /**
     * ArrayAccess
     * @param int|string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->results[$offset];
    }

    /**
     * ArrayAccess
     * @param int|string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->results[] = $value;
        } else {
            $this->results[$offset] = $value;
        }
    }

    /**
     * ArrayAccess
     * @param int|string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->results[$offset]);
    }


    /**
     * Call a method on all models in a result set. This allows for method
     * chaining such as setting a property on all models in a result set or
     * any other batch operation across models.
     * @example ORM::forTable('Widget')->findMany()->set('field', 'value')->save();
     * @param string $method
     * @param array $params
     * @return ResultSet
     */
    public function __call($method, $params = array())
    {
        foreach ($this->results as $model) {
            call_user_func_array(array($model, $method), $params);
        }
        return $this;
    }
}
