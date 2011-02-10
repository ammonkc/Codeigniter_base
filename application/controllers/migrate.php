<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('MY_Controller'))
{
    if ( ! class_exists('CI_Controller'))
    {
    	class CI_Controller extends Controller {}
    }
	class MY_Controller extends CI_Controller {}
}

class Migrate extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('migration');

		/** VERY IMPORTANT - only turn this on when you need it. */
//		show_error('Access to this controller is blocked, turn me on when you need me.');
	}

	// Install up to the most up-to-date version.
	function install()
	{
		if ( ! $this->migration->current())
		{
			show_error($this->migration->error);
			exit;
		}

		echo "<br />Migration Successful<br />";
	}

	// This will migrate up to the configed migration version
	function version($id = NULL)
	{
		// No $id supplied? Use the config version
		$id OR $id = $this->config->item('migration_version');

		if ( ! $this->migration->version($id))
		{
			show_error($this->migration->error);
			exit;
		}

		echo "<br />Migration Successful<br />";
	}
}
