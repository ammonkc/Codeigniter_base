<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 * Adds one validation rule, "unique" and accepts a
 * parameter, the name of the table and column that
 * you are checking, specified in the forum table.column
 *
 * Note that this update should be used with the
 * form_validation library introduced in CI 1.7.0
 */
class MY_Form_validation extends CI_Form_validation {

	function My_Form_validation()
	{
	    parent::CI_Form_validation();
	}

	// --------------------------------------------------------------------

	/**
	 * Unique
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	function unique($str, $field)
	{
		$CI =& get_instance();
		list($table, $column) = split("\.", $field, 2);

		$CI->form_validation->set_message('unique', 'The %s that you requested is unavailable.');

		$query = $CI->db->query("SELECT COUNT(*) dupe FROM $table WHERE $column = '$str'");
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}
	
	/**
	 * Conditional Required
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function required_dependency($str, $val)
	{
	    if (!empty($val)) {
	        return TRUE;
	    }
	    else
	    {
    		if ( ! is_array($str))
    		{
    		    if ( trim($str) == '' )
    		    {
    		        $this->form_validation->set_message('required_dependency', '% is a required field.');
    		        return FALSE;
    		    }
    		    else
    		    {
    		        return TRUE;
    		    }
    		}
    		else
    		{
    		    if ( ! empty($str) )
    		    {
    		        $this->form_validation->set_message('required_dependency', '% is a required field.');
    		        return TRUE;
    		    }
    		    else
    		    {
    		        return FALSE;
    		    }
    		}
		}
	}
	
	// --------------------------------------------------------------------
}
?>