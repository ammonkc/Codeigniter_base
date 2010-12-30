<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Orbit
 *
 * This library allow users of PHP 5.3 to emulate a Singleton pattern in
 * CodeIgniter.  Users of PHP 5.1 or 5.2 will benefit from the global CI()
 * function.  Both the Singleton class and the CI() function are available
 * throught the entire application.
 *
 * PHP 5.3 USAGE EXAMPLES:
 * CI::load()->view('welcome_message');
 * CI::db()->query('SELECT * FROM `my_table`');
 *
 * PHP 5.1 - 5.2 USAGE EXAMPLES:
 * CI::$APP->load->view('welcome_message');
 * CI::$APP->db->query('SELECT * FROM `my_table`');
 *
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @package		CodeIgniter
 * @subpackage	Orbit
 * @version		1.0
 * @copyright	Copyright (c) 2010 Dan Horrigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * CI Orbit Class
 *
 * This class is available throught the entire application and allows you to
 * emulate a Singleton pattern for CodeIgniter.
 */
class CI {

	/**
	 * Holds the CodeIgniter object
	 *
	 * @access		public
	 * @author		Wiredesignz
	 * @copyright	Wiredesignz
	 * @staticvar	$APP
	 */
	public static $APP;

	/**
	 * Construct
	 *
	 * This SHOULD be set to private to block instantiation of the Singleton, however
	 * CI calls the construct, so we must make it public.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		// Load the global object
		self::$APP =& CI_Base::get_instance();
	}

	/**
	 * Clone
	 *
	 * This is set to private to block cloning of the Singleton.
	 *
	 * @author	Zack Kitzmiller
	 * @access	private
	 * @return	void
	 */
	private function __clone() { }

	/**
	 * Static Call Overload
	 *
	 * A PHP 5.3 function that allow anonymous static function calls. This allows
	 * the CI::lib_name() functionality.
	 *
	 * @param	string	$name		The name of function (library)
	 * @param	string	$arguments	This is unused, but required by __callStatic()
	 * @return	object
	 */
	public static function  &__callStatic($name, $arguments)
	{
		return self::$APP->{$name};
	}

}

/**
 * Global CI object
 *
 * Example:
 * CI()->db->get('table');
 *
 * @staticvar	object	$ci
 * @return		object
 * @depreciated
 */
function CI()
{
    return CI::$APP;
}

/* End of file CI.php */