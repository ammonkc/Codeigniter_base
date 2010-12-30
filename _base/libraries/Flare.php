<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Flare is a very simple, elegant yet powerful
 * Object Relational Mapper for CodeIgniter applications.
 *
 * @package Flare
 * @license Creative Commons Attribution License http://creativecommons.org/licenses/by/2.0/uk/
 * @link http://bitbucket.org/jamierumbelow/flare
 * @version 1.0.0
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @copyright Copyright (c) 2009, Jamie Rumbelow <http://jamierumbelow.net>
*/

require_once "flare/flare_helpers.php";
require_once "flare/flare_relationships.php";

class Flare {
	
	/**
	 * The table name, automatically generated
	 * based on the class name (pluralised).
	 *
	 * Create a table_name() method to overload
	 *
	 * @var string
	 */
	static $table;
	
	/**
	 * The name of the primary key. Defaults to
	 * ID but can be overloaded if needs be.
	 *
	 * Create a primary_key() method to overload
	 *
	 * @var string
	 */
	static $primary_key;
	
	/**
	 * The CodeIgniter database object. Use as a 
	 * pointer to the DB class if you need to.
	 *
	 * @var object
	 */
	static $db;
	
	/**
	 * Whether or not the record is a new record
	 * or not.
	 *
	 * @var boolean
	 */
	public $new_record = TRUE;
	
	/**
	 * Any has_many relationships, for example
	 * Post -> has_many -> categories.
	 *
	 * This is essentially the other side of a
	 * belongs_to association, just like has_one,
	 * so no DB stuff is needed.
	 *
	 * @var array
	 */
	public $has_many = array();
	
	/**
	 * Any has_and_belongs_to_many relationships,
	 * for instance Post -> has_and_belongs_to_many -> tags.
	 *
	 * This signifies a joining table - for example, if we had
	 * two models, a Post model that had and belonged to many Category
	 * models, we'd need to create a categories_posts table. It works
	 * on alphabetical order, so an Item and Box HABTM relationship would
	 * look for a boxes_items table, and so on.
	 *
	 * @var array
	 */
	public $has_and_belongs_to_many = array();
	
	/**
	 * Any has_one relationships, for example
	 * Post -> has_one -> blog
	 * 
	 * The other side of a belongs_to relationship,
	 * so no DB stuff is ever needed.
	 *
	 * @var array
	 */
	public $has_one = array();
	
	/**
	 * Any belongs_to relationships, for example
	 * a Post -> belongs_to -> blog
	 *
	 * You'll need a foreign key in your database:
	 * generally model_id but can be customised if
	 * it's an array (through the foreign_key parameter).
	 *
	 * @var array
	 */
	public $belongs_to = array();
	
	/**
	 * The class' raw attributes saved as an array.
	 *
	 * @var array
	 */
	protected $attributes 		= array();
	
	/**
	 * An array of attributes that have been changed
	 * since last save. Used to keep track of what to
	 * update and what to leave.
	 *
	 * @var array
	 */
	protected $dirty_attributes = array();
	
	public $__pk;
	
	/**
	 * Constructor - can be passed an array of attributes
	 * and a boolean to check if it's a new record or not.
	 *
	 * @param array $attributes An array of attributes
	 * @param boolean $new_record Is the object a new record?
	 * @author Jamie Rumbelow
	 */
	public function __construct($attributes = null, $new_record = TRUE) {
		$this->new_record = $new_record;
		$this->__pk       = self::$primary_key;
		
		if (isset($attributes)) {
			foreach ((array)$attributes as $name => $value) {
				$this->attributes[$name] = $value;
				
				if ($new_record) {
					$this->dirty_attributes[] = $name;
				}
			}
		}
	}
	
	/**
	 * Magic function for getting object attributes.
	 *
	 * MH Note: The original condition to check the relationship was duplicated within the
	 * `relationship` method. So, I dropped the condition here and relied on the one in
	 * `relationship` so we weren't repeating ourselves.
	 *
	 * @param string $attr Attribute to get (variable being accessed)
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public function __get($attr) {
		if ($relation = $this->relationship($attr))
		{
			return $relation;
		}
		
		if (in_array($attr, array_keys($this->attributes))) {
			return $this->attributes[$attr];
		}
	}
	
	/**
	 * Initialise a relationship and return either
	 * the model itself, or the proxy object.
	 *
	 * @param string $name The name of the relationship
	 * @return object
	 * @author Jamie Rumbelow
	 * @author Mark Huot
	 */
	public function relationship($name) {
		
		// determine type
		$type = '';
		if (in_array($name, array_merge($this->has_and_belongs_to_many, array_keys($this->has_and_belongs_to_many)), TRUE)) {
			$type = 'has_and_belongs_to_many';
		} else if (in_array($name, array_merge($this->has_many, array_keys($this->has_many)), TRUE)) {
			$type = 'has_many';
		} else if (in_array($name, array_merge($this->has_one, array_keys($this->has_one)), TRUE)) {
			$type = 'has_one';
		} else if (in_array($name, array_merge($this->belongs_to, array_keys($this->belongs_to)), TRUE)) {
			$type = 'belongs_to';
		}
		
		// make sure we're set
		if (!$type) { return FALSE; }
		
		// get the options
		$options = $this->{$type};
		
		// determine related class
		if (isset($this->{$type}[$name]['class'])) {
			$class = $this->{$type}[$name]['class'];
		} else {
			$class = ucwords(singularize($name));
		}
		
		// determine related table
		if (isset($this->{$type}[$name]['table'])) {
			$table = $this->{$type}[$name]['table'];
		} else {
			$table = strtolower(pluralize($class));
		}
		
		// determine primary key
		if (isset($this->{$type}[$name]['primary_key'])) {
			$primary_key = $this->{$type}[$name]['primary_key'];
		} else {
			if (in_array($type, array('has_one', 'belongs_to'), TRUE)) {
				$primary_key = singularize($name)."_id";
			} else {
				$primary_key = "id";
			}
		}
		
		// determine foreign key
		if (isset($this->{$type}[$name]['foreign_key'])) {
			$foreign_key = $this->{$type}[$name]['foreign_key'];
		} else {
			if (in_array($type, array('has_one', 'belongs_to'), TRUE)) {
				$foreign_key = "id";
			} else {
				$foreign_key = strtolower(get_class($this))."_id";
			}
		}
		
		// determine join table
		if (isset($this->{$type}[$name]['join_table'])) {
			$join_table = $this->{$type}[$name]['join_table'];
		} else {
			$join_table = strtolower(implode('_', value_sort(array(self::_table(), $table))));
		}
		
		// determine catchment
		$catchment = array($foreign_key => $this->attributes[self::$primary_key]);
		
		// determine join fields
		$join_fields = $join_table.".".strtolower($class)."_id=".$table.".id";
		
		// prep the relationship
		switch ($type)
		{
			case 'has_and_belongs_to_many':
				return new Flare_multiple_relationship_proxy($class, $table, $catchment, $join_table, $join_fields);
				break;
			
			case 'has_many':
				return new Flare_multiple_relationship_proxy($class, $table, $catchment);
				break;
			
			case 'has_one':
			case 'belongs_to':
				$object = self::$db->where($foreign_key, $this->attributes[$primary_key])->get($table)->row();
				$attributes = array();
				
				foreach ($object as $key => $value) {
					$attributes[$key] = $value;
				}
			
				return new $class($attributes, FALSE);
				break;
		}
	}
	
	/**
	 * Magic function for setting variable attributes
	 *
	 * @param string $attr Attribute to set
	 * @param string $value Its value
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function __set($attr, $value) {
		$this->attributes[$attr] = $value;
		$this->dirty_attributes[] = $attr;
	}
	
	/**
	 * Magic function to return primary key when used in
	 * the context of a string. Means you can pass the object
	 * to functions like anchor().
	 *
	 * If a to_param() method is present in your model, you can
	 * use another attribute instead of the primary key.
	 *
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public function __tostring() {
		return (method_exists($this, 'to_param')) ? $this->to_param() : $this->attributes[self::$primary_key];
	}
	
	/**
	 * Save the record - create if new or update if it already
	 * exists. Only updates params in the dirty_attributes array.
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function save() {
						   get_instance()->load->database();
		self::$db 		=& get_instance()->db;
		self::$table 	=  $this->_table();
		self::$primary_key 	=  $this->_prim_key();
		
		// handle timestamps
		if (in_array('created_at', array_keys($this->attributes)) && $this->new_record) {
			$this->attributes['created_at'] = date('Y-m-d H:i:s');
		}
		
		if (in_array('updated_at', array_keys($this->attributes))) {
			$this->attributes['updated_at'] = date('Y-m-d H:i:s');
		}
		
		// what attributes are we to change, good sir?
		foreach ($this->dirty_attributes as $attr) {
			self::$db->set($attr, $this->attributes[$attr]);
		}
		
		if (!$this->new_record) {
			self::$db->where(self::$primary_key, $this->attributes[self::$primary_key]);
		}
		
		$verdict = ($this->new_record) ? self::$db->insert($this->_table()) : self::$db->update($this->_table());
		
		if ($verdict) {
			$this->dirty_attributes = array();
			
			if ($this->new_record) {
				$this->attributes[self::$primary_key] = self::$db->insert_id();
			}
			
			$this->new_record = FALSE;
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Update the current object with the data passed through.
	 *
	 * @param array $data The data to update with
	 * @return boolean
	 * @author Jamie Rumbelow
	 */
	public function update($data) {
						   get_instance()->load->database();
		self::$db 		=& get_instance()->db;
		self::$table 	=  $this->_table();
		self::$primary_key 	=  $this->_prim_key();
		
		return self::$db->where(self::$primary_key, $this->attributes[self::$primary_key])
						->update(self::$table, $data);
	}
	
	/**
	 * Destroy the current object.
	 *
	 * @return boolean
	 * @author Jamie Rumbelow
	 */
	public function destroy() {
						   	get_instance()->load->database();
		self::$db 			=& get_instance()->db;
		self::$table 		=  $this->_table();
		self::$primary_key 	=  $this->_prim_key();
		
		return self::$db->where(self::$primary_key, $this->attributes[self::$primary_key])
						->delete(self::$table);
	}
	
	/**
	 * Get the table name, based off the class name, in a
	 * static context. Similar to $this->_table().
	 *
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public static function table() {
		if (method_exists(get_called_class(), 'table_name')) {
			return call_user_func_array(array(get_called_class(), 'table_name'), array());
		} else {
			return (!isset(self::$table)) ? pluralize(strtolower(get_called_class())) : self::$table;
		}
	}
	
	/**
	 * Return the table name, in a local context.
	 *
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public function _table() {
		if (method_exists($this, 'table_name')) {
			return call_user_func_array(array($this, 'table_name'), array());
		} else {
			return (!isset(self::$table)) ? pluralize(strtolower(get_class($this))) : self::$table;
		}
	}
	
	/**
	 * Return the primary key name, in a local context.
	 *
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public static function prim_key() {
		if (method_exists(get_called_class(), 'primary_key')) {
			return call_user_func_array(array(get_called_class(), 'primary_key'), array());
		} else {
			return 'id';
		}
	}
	
	/**
	 * Return the primary key name, in a local context.
	 *
	 * @return string
	 * @author Jamie Rumbelow
	 */
	public function _prim_key() {
		if (method_exists($this, 'primary_key')) {
			return call_user_func_array(array($this, 'primary_key'), array());
		} else {
			return 'id';
		}
	}
	
	/**
	 * Find a result or result set and return the array. Find operates with four different retrieval approaches:
	 *
	 * 		Find by primary key - Pass through the primary key, usually an ID. Use Model::find(1)
	 * 		Find first 			- This will return the first record matched by the options used. Use Model::find('first', array()) or its shortcut Model::first(array()).
	 * 		Find last 			- This will return the last record matched by the options used. Use Model::find('last', array()) or its shortcut Model::last(array()).
	 * 		Find all 			- This will return all the records matched by the options used. Use Model::find('all', array()) or its shortcut Model::all(array()). 
	 *      		   			  You can also pass through an options array straight to find()
	 *
	 * All approaches accept an options array as their last parameter. The options array has the following possible options:
	 *
	 * 		:conditions - A standard CodeIgniter where parameter, so either a SQL fragment ('name = "Jamie"') or an associative array (array('name' => "Jamie"))
	 * 		:order 		- An SQL fragment like "created_at DESC".
	 *		:group 		- An attribute name by which the result should be grouped. Uses the GROUP BY SQL-clause.
	 *		:having 	- Combined with :group this can be used to filter the records that a GROUP BY returns. Uses the HAVING SQL-clause.
	 *		:limit 		- An integer determining the limit on the number of rows that should be returned.
	 *		:offset 	- An integer determining the offset from where the rows should be fetched. So at 5, it would skip rows 0 through 4.
	 *		:joins 		- An array with three values: the table to join, the clause of the JOIN, and the type of join ('left', 'right', 'inner')
	 * 		:include 	- Named associations that should be loaded alongside.
	 *		:select 	- A string. By default, this is "*" as in "SELECT * FROM", but can be changed if you, for example, want to do a join but not include the joined columns.
	 *		:from 		- By default, this is the table name of the class, but can be changed to an alternate table name (or even the name of a database view).
	 *
	 * @param Either the Primary Key, Options array or 'all', 'first' or 'last'
	 * @param array $options The options array
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function find() {
		$class = get_called_class();
		self::$primary_key 	=  self::prim_key();
	
		if (func_num_args() <= 0) {
			throw new RecordNotFound("Couldn't find $class without an ID");
			return;
		}
		
		$args 	  = func_get_args();
		$options  = (isset($args[1])) ? $args[1] : array();
	
		if ($args[0] == 'all' || $args[0] == 'first' || $args[0] == 'last') {
			switch ($args[0]) {
				case 'all':
					$result = self::call_codeigniter_methods($options)
									->get(self::$table)
									->result();
					break;
				
				case 'first':
					$result = self::call_codeigniter_methods($options)
									->get(self::$table, 1)
									->row();
					break;
				
				case 'last':
					$result = self::call_codeigniter_methods($options)
									->get(self::$table)
									->last_row();
					break;
			}
		} else {
			if (is_array($args[0])) {
				$result = self::call_codeigniter_methods($options)
								->get(self::$table)
								->result();
			} elseif (is_integer($args[0]) || is_string($args[0])) {
				$result = self::call_codeigniter_methods(array('conditions' => array(self::$primary_key => $args[0])))
								->get(self::$table)
								->row();
			}
		}
				
		return self::parse_result($result);
	}
	
	/**
	 * A wrapper for Model::find('all', $options)
	 *
	 * @param array $options The options array
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function all($options = array()) {
		return self::find('all', $options);
	}
	
	/**
	 * A wrapper for Model::find('first', $options)
	 *
	 * @param array $options The options array
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function first($options = array()) {
		return self::find('first', $options);
	}
	
	/**
	 * A wrapper for Model::find('last', $options)
	 *
	 * @param array $options The options array
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function last($options = array()) {
		return self::find('last', $options);
	}
	
	/**
	 * Saves a data array and returns its object.
	 *
	 * @param array $data The data array
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function create($data) {
						   get_instance()->load->database();
		self::$db 		=& get_instance()->db;
		self::$table 	=  self::table();
		self::$primary_key 	=  self::prim_key();
		
		self::$db->insert(self::$table, $data);
		$id = self::$db->insert_id();
		
		return self::find($id);
	}
	
	/**
	 * Delete a record, specified by either its primary
	 * key or an options array
	 *
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function delete() {
		$args = func_get_args();
		
		self::$primary_key 	=  self::prim_key();
		
		if (is_array($args[0])) {
			$options = $args[0];
		} else {
			$options = array('conditions' => array(self::$primary_key => $args[0]));
		}
		
		return self::call_codeigniter_methods($options)
					 ->delete(self::$table);
	}
	
	/**
	 * Translate the options hash into CodeIgniter ActiveRecord
	 * functions. Returns the CI DB object.
	 *
	 * @param string $options The options hash
	 * @param boolean $local Are we calling it statically? 
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function call_codeigniter_methods($options, $local = FALSE) {
						   get_instance()->load->database();
		self::$db 		=& get_instance()->db;
		self::$table 	=  ($local) ? $this->_table() : self::table();
		self::$primary_key 	=  ($local) ? $this->_prim_key() : self::prim_key();
		
		/* WHERE clause */
		if (isset($options['conditions'])) {
			self::$db->where($options['conditions']);
		}
		
		/* ORDER BY */
		if (isset($options['order'])) {
			self::$db->order_by($options['order']);
		}
		
		/* GROUP BY */
		if (isset($options['group'])) {
			self::$db->group_by($options['group']);
		}
		
		/* HAVING */
		if (isset($options['having'])) {
			self::$db->having($options['having']);
		}
		
		/* LIMIT and OFFSET */
		if (isset($options['limit'])) {
			if (isset($options['offset'])) {
				self::$db->limit($options['limit'], $options['offset']);
			} else {
				self::$db->limit($options['limit']);
			}
		}
		
		/* JOINs */
		if (isset($options['join'])) {
			self::$db->join($options['join'][0], $options['join'][1], $options['join'][2]);
		}
		
		/* Associated JOINs */
		if (isset($options['include'])) {
			foreach (self::$associations as $association) {
				if (is_array($association)) {
					$association = pluralize($association[0]);
					$primary_key = $association[1];
				} else {
					$association = pluralize($association[0]);
					$primary_key = 'id';
				}
				
				self::$db->join($association, $association . '.' . $primary_key . ' = ' . self::$primary_key, 'inner');
			}
		}
		
		/* SELECT */
		if (isset($options['select'])) {
			self::$db->select($options['select']);
		}
		
		/* FROM */
		if (isset($options['from'])) {
			self::$table = $options['from'];
		}
		
		/* And return the DB object! */
		return self::$db;
	}
	
	/**
	 * Takes an object or array raw from the database and returns 
	 * it as a Flare object. Generally only used internally.
	 *
	 * @param array $result Result array or object
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public static function parse_result($result) {
		$return = null;
		$class  = get_called_class();
		
		if (is_array($result)) {
			foreach ($result as $object) {
				$return[] = new $class($object, FALSE);
			}
		} else {
			$return = new $class($result, FALSE);
		}
		
		return $return;
	}
	
	/**
	 * Does the same as parse_result(), only in a local context
	 * rather than a static one.
	 *
	 * @param array $result Result array or object
	 * @return object
	 * @author Jamie Rumbelow
	 */
	public function parse_result_locally($result) {
		$return = null;
		$class  = get_class($this);
		
		if (is_array($result)) {
			foreach ($result as $object) {
				$return[] = new $class($object, FALSE);
			}
		} else {
			foreach ($result as $key => $value) {
				$this->attributes[$key] = $value;
				$this->new_record = FALSE;
			}
		}
		
		return $return;
	}
	
}