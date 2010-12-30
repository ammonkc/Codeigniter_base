<?php
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

/**
 * The proxy to a has_many relationship. Is instantiated
 * for each relationship, and contains details about the
 * relationship and functionality to access it.
 *
 * @package flare
 * @author Jamie Rumbelow
 */
class Flare_multiple_relationship_proxy {
	private $db;
	
	private $catchment;
	private $join_table;
	private $join_fields;
	private $class;
	
	private $table;
	
	public function __construct($class, $table, $catchment = array(), $join_table = '', $join_fields = '') {
		$this->catchment   = $catchment;
		$this->join_table  = $join_table;
		$this->join_fields = $join_fields;
		$this->class 	   = $class;
		$this->table 	   = $table;
		
		$this->db		 = get_instance()->db;
	}
	
	/**
	 * String Generator Magic Method
	 * 
	 * MH Note: Added so you could dump out a collection without needing to actually loop over the
	 * contents. It'd be nice if there was a way to conditionally set the separator though.
	 *
	 * @return string
	 * @author Mark Huot
	 */
	public function __toString()
	{
		if ($this->find('all'))
		{
			return implode(', ', $this->find('all'));
		}
		
		return '';
	}
	
	public function all($options = array()) {
		return $this->find('all', $options);
	}
	
	public function first($options = array()) {
		return $this->find('first', $options);
	}
	
	public function last($options = array()) {
		return $this->find('last', $options);
	}
	
	public function find() {
		if (func_num_args() <= 0) {
			throw new RecordNotFound("Couldn't find $class without an ID");
			return;
		}
	
		$args 	  = func_get_args();
		$options  = (isset($args[1])) ? $args[1] : array();
		$class    = $this->class;
	
		if ($args[0] == 'all' || $args[0] == 'first' || $args[0] == 'last') {
			switch ($args[0]) {
				case 'all':
					$this->call_codeigniter_methods($options);
					if ($this->join_table) { $this->db->join($this->join_table, $this->join_fields); }
					$this->db->where($this->catchment);
					
					return $this->parse_result($this->db->get($this->table)->result());
					break;
					
				case 'first':
					$this->call_codeigniter_methods($options);
					if ($this->join_table) { $this->db->join($this->join_table, $this->join_fields); }
					$this->db->where($this->catchment);
					
					return $this->parse_result($this->db->get($this->table)->row());
					break;
					
				case 'last':
					$this->call_codeigniter_methods($options);
					if ($this->join_table) { $this->db->join($this->join_table, $this->join_fields); }
					$this->db->where($this->catchment);

					return $this->parse_result($this->db->get($this->table)->last_row());
					break;
			}
		} else {
			if (is_array($args[0])) {
				$this->call_codeigniter_methods($options);
				
				return $this->parse_result(
							$this->db->where($this->catchment)
									 ->join($this->join_table, $this->join_fields)
						 			 ->get($this->table)
						 			 ->result()
					   );
			} elseif (is_integer($args[0]) || is_string($args[0])) {
				$pk = new $class();
				$pk = $pk->__pk;
				
				$this->call_codeigniter_methods(array('conditions' => array($pk => $args[0])));
				
				return $this->parse_result(
							$this->db->where($this->catchment)
									 ->join($this->join_table, $this->join_fields)
									 ->get($this->table)
									 ->row()
					   );
			}
		}
	}
	
	public function create($data) {
		$this->db->set($this->catchment);
		$this->db->set($data);
		
		return $this->db->insert($this->table);
	}
	
	public function delete($data) {
		if (is_array($data)) {
			if ($this->join_table) { $this->db->join($this->join_table, $this->join_fields); }
			$this->db->where($data);
		} else {
			$class = $this->class;
			$pk = new $class();
			$pk = $pk->__pk;
			
			if ($this->join_table) { $this->db->join($this->join_table, $this->join_fields); }
			$this->db->where($pk, $data);
		}
		
		return $this->db->delete($this->table);
	}
	
	public function call_codeigniter_methods($options) {
		/* WHERE clause */
		if (isset($options['conditions'])) {
			$this->db->where($options['conditions']);
		}
		
		/* ORDER BY */
		if (isset($options['order'])) {
			$this->db->order_by($options['order']);
		}
		
		/* GROUP BY */
		if (isset($options['group'])) {
			$this->db->group_by($options['group']);
		}
		
		/* HAVING */
		if (isset($options['having'])) {
			$this->db->having($options['having']);
		}
		
		/* LIMIT and OFFSET */
		if (isset($options['limit'])) {
			if (isset($options['offset'])) {
				$this->db->limit($options['limit'], $options['offset']);
			} else {
				$this->db->limit($options['limit']);
			}
		}
		
		/* JOINs */
		if (isset($options['join'])) {
			$this->db->join($options['join'][0], $options['join'][1], $options['join'][2]);
		}
		
		/* SELECT */
		if (isset($options['select'])) {
			$this->db->select($options['select']);
		}
	}
	
	public function parse_result($result) {
		$return = null;
		$class  = $this->class;
		
		if (is_array($result)) {
			foreach ($result as $object) {
				$return[] = new $class($object, FALSE);
			}
		} else {
			foreach ($result as $key => $value) {
				$data[$key] = $value;
			}
			
			$return = new $class($data, FALSE);
			$return->new_record = FALSE;
		}
		
		return $return;
	}
}