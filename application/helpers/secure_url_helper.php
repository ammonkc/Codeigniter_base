<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 2009-09-30 ammon
 * Handles dynamic switching to https
 * http://sajjadhossain.com/2008/10/27/ssl-https-urls-and-codeigniter/
 */

if( ! function_exists('secure_site_url') )
{
    function secure_site_url($uri = '')
    {
        $CI =& get_instance();
        return $CI->config->secure_site_url($uri);
    }
}
 
if( ! function_exists('secure_base_url') )
{
    function secure_base_url()
    {
        $CI =& get_instance();
        return $CI->config->slash_item('secure_base_url');
    }
}
 
if ( ! function_exists('secure_anchor'))
{
    function secure_anchor($uri = '', $title = '', $attributes = '')
    {
        $title = (string) $title;
 
        if ( ! is_array($uri))
        {
            $secure_site_url = ( ! preg_match('!^\w+://! i', $uri)) ? secure_site_url($uri) : $uri;
        }
        else
        {
            $secure_site_url = secure_site_url($uri);
        }
 
        if ($title == '')
        {
            $title = $secure_site_url;
        }
 
        if ($attributes != '')
        {
            $attributes = _parse_secure_attributes($attributes);
        }
 
        return '<a href="'.$secure_site_url.'" '.$attributes.'>'.$title.'</a>';
    }
}
 
if ( ! function_exists('secure_redirect'))
{
    function secure_redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".secure_site_url($uri));
                break;
            default            : header("Location: ".secure_site_url($uri), TRUE, $http_response_code);
                break;
        }
        exit;
    }
}

/**
 * Secure Form Declaration
 *
 * Creates the opening portion of the form.
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */	
if ( ! function_exists('secure_form_open'))
{
	function secure_form_open($action = '', $attributes = '', $hidden = array())
	{
		$CI =& get_instance();

		if ($attributes == '')
		{
			$attributes = 'method="post"';
		}

		$action = ( strpos($action, '://') === FALSE) ? $CI->config->secure_site_url($action) : $action;

		$form = '<form action="'.$action.'"';
	
		$form .= _attributes_to_string($attributes, TRUE);
	
		$form .= '>';

		if (is_array($hidden) AND count($hidden) > 0)
		{
			$form .= form_hidden($hidden);
		}

		return $form;
	}
}

// ------------------------------------------------------------------------

/**
 * Parse out the attributes
 *
 * Some of the functions use this
 *
 * @access	private
 * @param	array
 * @param	bool
 * @return	string
 */
if ( ! function_exists('_parse_secure_attributes'))
{
	function _parse_secure_attributes($attributes, $javascript = FALSE)
	{
		if (is_string($attributes))
		{
			return ($attributes != '') ? ' '.$attributes : '';
		}

		$att = '';
		foreach ($attributes as $key => $val)
		{
			if ($javascript == TRUE)
			{
				$att .= $key . '=' . $val . ',';
			}
			else
			{
				$att .= ' ' . $key . '="' . $val . '"';
			}
		}

		if ($javascript == TRUE AND $att != '')
		{
			$att = substr($att, 0, -1);
		}

		return $att;
	}
}

/* End of file secure_url_helper.php */
/* Location: ./application/helpers/secure_url_helper.php */