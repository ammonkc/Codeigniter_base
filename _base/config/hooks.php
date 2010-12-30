<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

/* 2008-07-08 ammonkc
 * Yielder Layout
 * http://hasin.wordpress.com/2007/03/05/adding-yield-codeigniter/
 */
 /*
$hook['display_override'][] = array('class' => 'Yielder',
								 'function' => 'yield',
								 'filename' => 'Yielder.php',
								 'filepath' => 'hooks');
*/
/* UhOh
 * Exceptions - Error handler
 * This is required to load the Exceptions library early enough
 */
$hook['pre_system'] = array(
	'function' => 'load_exceptions',
	'filename' => 'uhoh.php',
	'filepath' => 'hooks',
);

/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */