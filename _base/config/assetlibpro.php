<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* AssetLibPro - A CodeIgniter Asset Class
*
* @version of AssetLibPro:	1.0.5
*/

/*
|--------------------------------------------------------------------------
| Asset storage
|--------------------------------------------------------------------------
|
| The path to where to storage the joined assets.
| alp_cache_dir_css = "/assets/compressed/" (as example)
|
*/
$config['alp_asset_dir'] = '/assets/';//TRAILING SLASH!
$config['alp_cache_dir_css'] = '/assets/compressed/css/';//TRAILING SLASH!
$config['alp_cache_dir_js'] = '/assets/compressed/js/';//TRAILING SLASH!

/*
|--------------------------------------------------------------------------
| Autoload assets
|--------------------------------------------------------------------------
|
| This allows you to autoload commonly used assets by default
|
|
*/
$config['alp_enable_autoload_css'] = FALSE;
$config['alp_enable_autoload_js'] = TRUE;
$config['alp_autoload_css'] = array('css/styles.css');
$config['alp_autoload_js'] = array('javascript/jquery-1.4.3.min.js','javascript/global.js');

/*
|--------------------------------------------------------------------------
| Asset default groups
|--------------------------------------------------------------------------
|
| This allows you set a default group adding assets without having to
| particularly specify the group on every "add_css()" or "add_js()"
|
*/
$config['alp_default_group_css'] = 'screen';
$config['alp_default_group_js'] = 'default';

/*
|--------------------------------------------------------------------------
| Toggle CSSTidy/JSMin Compression
|--------------------------------------------------------------------------
|
| Whether to run compression using csstidy or jsmin
|
*/
$config['alp_enable_csstidy'] = TRUE;
$config['alp_enable_jsmin'] = TRUE;

/*
|--------------------------------------------------------------------------
| Toggle GZip Compression
|--------------------------------------------------------------------------
|
| Whether to compression the output using gzip
|
*/
$config['alp_gzip_compress_css'] = TRUE;
$config['alp_gzip_compress_js'] = TRUE;

/*
|--------------------------------------------------------------------------
| Toggle Browser Caching
|--------------------------------------------------------------------------
|
| Whether to force the browser to cache the files
| (Even with caching enabled web browsers will still detect changes automatically!)
|
*/
$config['alp_force_cache_css'] = TRUE;
$config['alp_force_cache_js'] = TRUE;

/*
|--------------------------------------------------------------------------
| Alternative development server configuration
|--------------------------------------------------------------------------
|
| Allows you to use different settings for your development (localhost?) server
|
*/
$enable_dev_server_config = TRUE;
$dev_server_name = 'localhost'; //Replace "localhost" with your dev server name if needed or just use a custom conditional expression.
if ($_SERVER['SERVER_NAME'] == $dev_server_name && $enable_dev_server_config == TRUE) {
	$config['alp_enable_csstidy'] = FALSE;
	$config['alp_enable_jsmin'] = FALSE;
	
	$config['alp_gzip_compress_css'] = FALSE;
	$config['alp_gzip_compress_js'] = FALSE;
	
	error_reporting(E_ALL);//Comment it out if not wanted.
}

/*
|--------------------------------------------------------------------------
| CSSTidy Config
|--------------------------------------------------------------------------
|
| The path from your site's root in which the csstidy folder is. Note
| this is from the site's root, not the file system root. Also note the
| required slashes at start and finish.
|
| csstidy_basepath = "/system/plugins/csstidy" (as example)
|
*/
$config['alp_csstidy_basepath']    = "../application/plugins/csstidy/";

$config['alp_csstidy_config'] = array(
							      'remove_bslash' => TRUE,
							      'compress_colors' => TRUE,
							      'compress_font-weight' => TRUE,
							      'lowercase_s' => FALSE,
							      'optimise_shorthands' => 1,
							      'remove_last_,' => TRUE,
							      'case_properties' => 1,
							      'sort_properties' => FALSE,
							      'sort_selectors' => FALSE,
							      'merge_selectors' => 2,
							      'discard_invalid_properties' => FALSE,
							      'css_level' => 'CSS2.1',
							      'preserve_css' => TRUE,
							      'timestamp' => FALSE
								 );
$config['alp_csstidy_template'] = "highest_compression";

/*
|--------------------------------------------------------------------------
| JSmin Config
|--------------------------------------------------------------------------
|
| Enter the path to your jsmin.php file. (relative from BASEPATH)
|
| jsmin = "/system/plugins/jsmin1.1.1.php" (as example)
|
*/
$config['alp_jsmin']    = "../application/plugins/jsmin.php";

/* End of file assetlibpro.php */
/* Location: ./application/config/assetlibpro.php */