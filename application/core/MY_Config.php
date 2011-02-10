<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * @author		2009-09-27 ammonkc
 * @copyright	Copyright (c) 2009, broken.paradigm.labs
 * @reference 	http://sajjadhossain.com/2008/10/27/ssl-https-urls-and-codeigniter/
 * @link 		
 */

/**
 * Extends Input Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Config
 * @author		Ammon Casey @ammonkc
 * @link		http://codeigniter.com/user_guide/libraries/input.html
 */

// CI 2.0 Compatibility 
if (!class_exists('CI_Config')){ class CI_Config extends Config {} }

class MY_Config extends CI_Config {
	
	function secure_site_url($uri = '')
	{
	    if (is_array($uri))
	    {
	        $uri = implode('/', $uri);
	    }

	    if ($uri == '')
	    {
	        return $this->slash_item('secure_base_url').$this->item('index_page');
	    }
	    else
	    {
	        $suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
	       return $this->slash_item('secure_base_url').$this->slash_item('index_page').preg_replace("|^/*(.+?)/*$|", "\\1", $uri).$suffix;
	    }
	}
}

/* End of file MY_Config.php */
/* Location: ./application/libraries/MY_Config.php */