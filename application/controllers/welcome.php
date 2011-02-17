<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('MY_Controller'))
{
    if ( ! class_exists('CI_Controller'))
    {
    	class CI_Controller extends Controller {}
    }
	class MY_Controller extends CI_Controller {}
}

class Welcome extends MY_Controller 
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
        /**
         * ------------------------------------------------------------------
         * CSS style block that goes in the <head/> tag
         * ------------------------------------------------------------------
         * @param This is edited on my local development repo
         * @author Ammon Casey
         * @return void
         * 
         **/
        $this->head_block = '<style type="text/css">
        							body	{ background-image: none; background-color: #fff; margin: 40px; font-family: Lucida Grande, Verdana, Sans-serif; font-size: 14px; color: #4F5155; }
        							a		{ color: #003399; background-color: transparent; font-weight: normal; }
        							h1		{ color: #444; background-color: transparent; border-bottom: 1px solid #D0D0D0; font-size: 16px; font-weight: bold; margin: 24px 0 2px 0; padding: 5px 0 6px 0; }
        							code	{ font-family: Monaco, Verdana, Sans-serif; font-size: 12px; background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0 14px 0; padding: 12px 10px 12px 10px; }
        					 </style>';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */