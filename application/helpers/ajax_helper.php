<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* isAjax
*
* This function does the following:
*
* Checks if the request is an AJAX request 
* 
* by checking the $_SERVER['HTTP_X_REQUESTED_WITH'] variable
*
* @access	public
* @param	Request
* @return	bool
*/
function isAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
}

/* End of file ajax_helper.php */
/* Location: ./system/application/helpers/ajax_helper.php */