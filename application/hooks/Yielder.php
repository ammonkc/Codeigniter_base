<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 2008-07-08 ammonkc
 * Yielder Layout
 * http://hasin.wordpress.com/2007/03/05/adding-yield-codeigniter/
 */

/****  Improved in comments  ****/

class Yielder {

	function yield()
	{
		global $application_folder, $OUT;

		$CI =& get_instance();
		$output = $CI->output->get_output();

		if ( isset( $CI->layout ) )
		{
			$app_folder = $application_folder;
			
			if ( !preg_match( '/(.+).php$/', $CI->layout ) ) {
				$CI->layout .= '.php';
			}//END - if
			
//			$requested = BASEPATH . $app_folder . '/views/layouts/' . $CI->layout;
//			$default = BASEPATH . $app_folder . '/views/layouts/default.php';
			$requested = $app_folder . '/views/layouts/' . $CI->layout;
			$default = $app_folder . '/views/layouts/default.php';
			
			if (file_exists($requested)) {
				$layout = $CI->load->file($requested, true);
				$view = str_replace( "{yield}", $output, $layout );
			}else{
				$layout = $CI->load->file($default, true);
				$view = str_replace( "{yield}", $output, $layout);
			}//END - ifelse
		}else{
			$view = $output;
		}//END - ifelse
		$OUT->_display( $view );
	}//END - func
}//END - class

/* End of file Yielder.php */
/* Location: ./application/hooks/Yielder.php */