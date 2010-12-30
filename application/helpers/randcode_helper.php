<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**** Generates random string for Registry code/token ****/




function rand_string( $length=6 ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
	$size = strlen( $chars );
	$str = '';
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	return $str;
}

/* End of file randcode_helper.php */
/* Location: ./system/application/helpers/randcode_helper.php */