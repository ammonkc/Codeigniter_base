<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Times
|--------------------------------------------------------------------------
|
| http://jamieonsoftware.com/journal/2011/1/20/syntax-sugar-1-times-and-dates.html
| 
| I always define constants for my times and dates in - when using CodeIgniter, 
| the config/constants.php comes in very handy - which makes working with 
| timestamps read like plain English. It's also incredibly easy to do. 
| I define MINUTE to be 60 seconds, and then increment up the time ladder, 
| and define plurals as it reads better.
|
*/

define('MINUTE', 60);
define('MINUTES', MINUTE);
define('HOUR', 60*MINUTES);
define('HOURS', HOUR);

/*
|--------------------------------------------------------------------------
| Dates
|--------------------------------------------------------------------------
|
| http://jamieonsoftware.com/journal/2011/1/20/syntax-sugar-1-times-and-dates.html
|
*/

define('HALF_DAY', 12*HOURS);
define('HALF_DAYS', HALF_DAY);
define('DAY', 2*HALF_DAY);
define('DAYS', DAY);
define('WEEK', 7*DAYS);
define('WEEKS', WEEK);
define('MONTH', 4*WEEKS);
define('MONTHS', MONTH);
define('YEAR', 12*MONTHS);
define('YEARS', YEAR);
define('FORTNIGHT', 2*WEEKS);
define('DECADE', 10*YEARS);

/* End of file constants.php */
/* Location: ./application/config/constants.php */