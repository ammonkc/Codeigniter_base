<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* AssetLibPro - A CodeIgniter Asset Class
* http://code.google.com/p/assetlib-pro/
*
* AssetLibPro uses code from AssetLib (http://codeigniter.com/forums/viewthread/74659/),
* but does not have much in common with AssetLib anymore.
*
* In addition to AssetLib's 'jsmin' and 'csstidy' functionality AssetLibPro brings you the following:
*	1. It fixes relative url() paths in your css files to ensure their validity.
*	2. It has a smart versioning mechanism:
*		a) It re-creates compressed files automatically once a source file was changed
*		   making sure that your cached files are always up-to-date.
*		b) It sets HTTP headers for far-future caching expiration for forced browser caching.
*		c) It supports branching for the use of multiple CSS selections (like "screen" or "print").
*		d) It allows you to use multiple sets of css/js files per CI application for minimized performace overhead
*	3. It supports GZip compression of cached files to take bandwidth optimization a step further.
*
* @author of AssetLib:		Andre Eckardt
* @version of AssetLib:		0.1
*
* @author of AssetLibPro:	Vincent Esche
* @version of AssetLibPro:	1.0.5
* 
* AssetLibPro is licensed under the terms of the MIT license as reproduced below:
* 
* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
* The MIT License
* 
* Copyright (c) 2008 Vincent Esche
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
* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
*/
class Assetlibpro {

	/*
	DO NOT EDIT ANY CLASS PROPERTIES IN HERE,
	THAT'S SUPPOSED TO BE DONE IN THE CONFIG FILE!
	*/
	   
	var $CI;
	var $_assets = array(
						 'css' => array(),
						 'js' => array()
						);
	var $asset_groups = array('css' => array(), 'js' => array());
	
	var $enable_csstidy = FALSE;
	var $csstidy_loaded = FALSE;
	var $css;
	var $csstidy;
	var $csstidy_config = array();
	var $csstidy_template = '';
	
	var $enable_jsmin = FALSE;
	var $jsmin_loaded = FALSE;
	var $jsmin;
	var $js;
	
	var $default_group_css = '';
	var $default_group_js = '';
	
	var $renew_cache_css = array();
	var $renew_cache_js = array();
	
	var $enable_autoload_css = FALSE;
	var $enable_autoload_js = FALSE;
	var $autoload_css = array();
	var $autoload_js = array();
	
	var $asset_dir = '';
	
	var $cache_dir_css = '/';
	var $cache_dir_js = '/';
	
	var $cache_file_css = '';
	var $cache_file_js = '';
		
	var $gzip_compress_css = TRUE;
	var $gzip_compress_js = TRUE;
	
	var $force_cache_css = TRUE;
	var $force_cache_js = TRUE;
	
	function __construct() {
		$this->CI = get_instance();
		
		log_message('debug', 'Assetlibpro library loaded');
		
		$this->default_group_css = $this->CI->config->item('alp_default_group_css'); 
		$this->default_group_js = $this->CI->config->item('alp_default_group_js');
		
		$this->asset_dir = $this->CI->config->item('alp_asset_dir');
		
		$this->cache_dir_css = $this->CI->config->item('alp_cache_dir_css');
		$this->cache_dir_js = $this->CI->config->item('alp_cache_dir_js');
		
		// Changed because it was allways refreshing cache
		$this->cache_dir_css = realpath(trim($this->CI->config->item('alp_cache_dir_css'), "/"));
		$this->cache_dir_js = realpath(trim($this->CI->config->item('alp_cache_dir_js'), "/"));
		
		/****  Autoload assets  ****/
		$this->enable_autoload_css = $this->CI->config->item('alp_enable_autoload_css');
		$this->enable_autoload_js = $this->CI->config->item('alp_enable_autoload_js');
		$this->autoload_css = $this->CI->config->item('alp_autoload_css');
		$this->autoload_js = $this->CI->config->item('alp_autoload_js');
		if ( $this->enable_autoload_css && count($this->autoload_css) > 0 ) {
			foreach( $this->autoload_css as $key => $value ) {
				$this->add_css($value);
			}
		}
		if ( $this->enable_autoload_js && count($this->autoload_js) > 0 ) {
			foreach( $this->autoload_js as $key => $value ) {
				$this->add_js($value);
			}
		}
	}
	
	/**
	* Add a CSS asset to the queue
	* @param	Path to file (relative from CI's master index.php file)
	* @param	Option for defining a media type for CSS files.
	**/
	function add_css($file, $media = '', $module_name = NULL) {
		if (empty($media))
			$media = $this->default_group_css;
			
		if (!in_array($media, array('all', 'aural', 'braille', 'embossed', 'handheld', 'print', 'projection', 'screen', 'tty', 'tv')))
			return FALSE;
		if (is_array($file)) {
		    foreach($file as $value) {
		        $file_loc = $this->_asset_loc($value, $module_name, 'css');
		        $this->_add($file_loc, $media);
		    }
		}else{
    		$file_loc = $this->_asset_loc($file, $module_name, 'css');
    		$this->_add($file_loc, $media);
    		//$this->_add($file, $media);
		}
	}
	
	/**
	* Add a Javascript asset to the queue
	* @param	Path to file (relative from CI's master index.php file)
	* @param	Option for splitting up JS files.
	**/   	
	function add_js($file, $group = '', $module_name = NULL) {
		if (empty($group))
   		$group = $this->default_group_js;			
		if (!is_string($group))
   		return FALSE;
   		if (is_array($file)) {
   		    foreach($file as $value) {
   		        $file_loc = $this->_asset_loc($value, $module_name, 'js');
   		        $this->_add($file_loc, $group);
   		    }
   		}else{
    		$file_loc = $this->_asset_loc($file, $module_name, 'js');
    		$this->_add($file_loc, $group);
    		//$this->_add($file, $group);
		}
	}
	
	/**
	* Add an asset to the queue
	* @param	Path to file (relative from CI's master index.php file)
	* @param	Option for defining a media type for CSS files or just for splitting up JS files.
	**/
	private function _add($file, $group = NULL) {
		$file_path = $file;
		if ($group === NULL)
			return FALSE;
		
		$file = realpath(trim($file, "/"));
		if (file_exists($file) && is_readable($file)) {
			$fileinfo = pathinfo($file);
			
			if ($fileinfo['extension'] === 'js' || $fileinfo['extension'] === 'css') {
				$this->_assets[$fileinfo['extension']][$group][$file_path] = '';
				if (!in_array($group,$this->asset_groups[$fileinfo['extension']]))
					$this->asset_groups[$fileinfo['extension']][] = $group;
			}
		}
	}
	
	/**
	* Load the config values of the JS componente
	* @param	All the stuff that needs to be done before actuallyly processing any files
	**/
	private function _prepare_assets($type = 'all'){
		if ($type == 'all') {
			$this->_prepare_assets('css');
			$this->_prepare_assets('js');
		}
		if ($type == 'css') {
			$has_assets = FALSE;
			foreach ($this->_assets['css'] as $array) {
				if (!empty($array))
					$has_assets = TRUE;
					break;
			}
			if (!$has_assets)
				return FALSE;
				
			$this->cache_file_css = array($this->default_group_css => '');
			$options = array(
							 $this->gzip_compress_css,
							 $this->force_cache_css,
							 $this->enable_csstidy,
							 $this->csstidy_template,
							 $this->csstidy_config
							);
		} else if ($type == 'js') {
			$has_assets = FALSE;
			foreach ($this->_assets['js'] as $array) {
				if (!empty($array)) {
					$has_assets = TRUE;
					break;
				}
			}
			if (!$has_assets)
				return FALSE;
				
			$this->cache_file_js = array($this->default_group_js => '');
			$options = array(
							 $this->gzip_compress_js,
							 $this->force_cache_js,
							 $this->enable_jsmin
							);
		}
		foreach ($this->asset_groups[$type] as $group) {
			$assets = array_keys($this->_assets[$type][$group]);
			$mtimes = array();
			
			foreach ($assets as $asset) {
				$mtimes[] = filemtime(dirname(FCPATH).'/'.trim($asset,'/'));
			}
			
			$assets_hash = md5(implode('',$assets));
			$options_hash = md5(serialize($options));
			$changes_hash = md5(serialize($mtimes));
					
			$file_name = $type.'_'.$group.'_'.substr($assets_hash,0,8).'_'.substr($options_hash,0,8).'_'.substr($changes_hash,0,8).'.php';
			
			if ($type == 'css') {
				$this->cache_file_css[$group] = $file_name;
				$this->renew_cache_css[$group] = (file_exists($this->cache_dir_css.'/'.$this->cache_file_css[$group])) ? FALSE : TRUE;
				if ($this->renew_cache_css[$group])
					$this->_load_css_config();
			} else if ($type == 'js') {
				$this->cache_file_js[$group] = $file_name;
				$this->renew_cache_js[$group] = (file_exists($this->cache_dir_js.'/'.$this->cache_file_js[$group])) ? FALSE : TRUE;
				if ($this->renew_cache_js[$group])
					$this->_load_js_config();
			}
		}
	}
	
	/**
	* Load the config values of the JS componente
	* @param	Which's assets link to print (either 'css', 'js' or 'all')
	**/
	function output($type = 'all'){
		if ($type == 'all') {
			$css = $this->output('css');
			$js = $this->output('js');
			return "$css\n$js";//return string outputs of both functions.
		}
		
		$has_assets = FALSE;
		foreach ($this->_assets[$type] as $key => $array) {
			if (!empty($array)) {
				$has_assets = TRUE;
				break;
			}
		}
		if (!$has_assets)
			return FALSE;
			
		$this->_prepare_assets($type);
		if ($type == 'css') {
			foreach ($this->asset_groups[$type] as $group) {
				if ($this->renew_cache_css[$group] === TRUE) {
					
					$file_paths = array_keys($this->_assets[$type][$group]);
					foreach ($file_paths as $file_path) {
						$this->_assets[$type][$group][$file_path] = file_get_contents(realpath(trim($file_path, "/")));
					}
					
					$this->_unlink_old_caches($this->cache_dir_css, $this->cache_file_css[$group]);
					
					$this->_fix_css_urls($group);
					
					if ($this->enable_csstidy === TRUE){
						$this->csstidy->parse(implode(array_values($this->_assets[$type][$group]), "\n"));
						$this->css = $this->csstidy->print->plain();
					} else {
						$this->css = implode(array_values($this->_assets[$type][$group]), "\n");
					}
					$http_headers = '<?php header("Content-type: text/css; charset: UTF-8"); ?>';
					if ($this->gzip_compress_css === TRUE || $this->gzip_compress_css === TRUE) {
						$http_headers .= $this->_http_headers($type, $this->gzip_compress_css, $this->force_cache_css);
					}
					$this->css = $http_headers."\n".$this->css;
					file_put_contents($this->cache_dir_css.'/'.$this->cache_file_css[$group], $this->css);
				}
			}
		} else if ($type == 'js') {
			
			foreach ($this->asset_groups[$type] as $group) {
				if ($this->renew_cache_js[$group] === TRUE) {
				
					$file_paths = array_keys($this->_assets[$type][$group]);
					foreach ($file_paths as $file_path) {
						$this->_assets[$type][$group][$file_path] = file_get_contents(realpath(trim($file_path, "/")));
					}
		
					$this->_unlink_old_caches($this->cache_dir_js, $this->cache_file_js[$group]);
					
					if ($this->enable_jsmin === TRUE){
						$js = implode(array_values($this->_assets[$type][$group]));
						
						$this->js = JSmin::minify($js);
					} else {
						$this->js = implode(array_values($this->_assets[$type][$group]));
					}
					$http_headers = '<?php header("Content-type: text/javascript; charset: UTF-8"); ?>';
					if ($this->gzip_compress_js === TRUE || $this->force_cache_js === TRUE) {
						$http_headers .= $this->_http_headers($type, $this->gzip_compress_js, $this->force_cache_js);
					}
					$this->js = $http_headers."\n".$this->js;
					file_put_contents($this->cache_dir_js.'/'.$this->cache_file_js[$group], $this->js);		
				}
			}
		}
		$output = '';
		if ($type == 'css') {
			foreach ($this->asset_groups[$type] as $group) {
				$output .= "<link rel=\"stylesheet\" href=\"".base_url().ltrim($this->CI->config->item('alp_cache_dir_css'), "/").$this->cache_file_css[$group]."\" type=\"text/css\" media=\"".$group."\" />\n";
			}
		} else if ($type == 'js') {
			$output .= "<script src=\"".base_url().ltrim($this->CI->config->item('alp_cache_dir_js'), "/").$this->cache_file_js[$group]."\" type=\"text/javascript\"></script>\n";
		}
		return $output;
	}
	
	/**
	* Load the config values of the JS componente
	* @param	Which's assets link to print (either 'css', 'js' or 'all')
	**/
	function secure_output($type = 'all'){
		if ($type == 'all') {
			$css = $this->secure_output('css');
			$js = $this->secure_output('js');
			return "$css\n$js";//return string outputs of both functions.
		}
		
		$has_assets = FALSE;
		foreach ($this->_assets[$type] as $key => $array) {
			if (!empty($array)) {
				$has_assets = TRUE;
				break;
			}
		}
		if (!$has_assets)
			return FALSE;
			
		$this->_prepare_assets($type);
		if ($type == 'css') {
			foreach ($this->asset_groups[$type] as $group) {
				if ($this->renew_cache_css[$group] === TRUE) {
					
					$file_paths = array_keys($this->_assets[$type][$group]);
					foreach ($file_paths as $file_path) {
						$this->_assets[$type][$group][$file_path] = file_get_contents(realpath(trim($file_path, "/")));
					}
					
					$this->_unlink_old_caches($this->cache_dir_css, $this->cache_file_css[$group]);
					
					$this->_fix_css_urls($group);
					
					if ($this->enable_csstidy === TRUE){
						$this->csstidy->parse(implode(array_values($this->_assets[$type][$group]), "\n"));
						$this->css = $this->csstidy->print->plain();
					} else {
						$this->css = implode(array_values($this->_assets[$type][$group]), "\n");
					}
					$http_headers = '<?php header("Content-type: text/css; charset: UTF-8"); ?>';
					if ($this->gzip_compress_css === TRUE || $this->gzip_compress_css === TRUE) {
						$http_headers .= $this->_http_headers($type, $this->gzip_compress_css, $this->force_cache_css);
					}
					$this->css = $http_headers."\n".$this->css;
					file_put_contents($this->cache_dir_css.'/'.$this->cache_file_css[$group], $this->css);
				}
			}
		} else if ($type == 'js') {
			
			foreach ($this->asset_groups[$type] as $group) {
				if ($this->renew_cache_js[$group] === TRUE) {
				
					$file_paths = array_keys($this->_assets[$type][$group]);
					foreach ($file_paths as $file_path) {
						$this->_assets[$type][$group][$file_path] = file_get_contents(realpath(trim($file_path, "/")));
					}
		
					$this->_unlink_old_caches($this->cache_dir_js, $this->cache_file_js[$group]);
					
					if ($this->enable_jsmin === TRUE){
						$js = implode(array_values($this->_assets[$type][$group]));
						
						$this->js = JSmin::minify($js);
					} else {
						$this->js = implode(array_values($this->_assets[$type][$group]));
					}
					$http_headers = '<?php header("Content-type: text/javascript; charset: UTF-8"); ?>';
					if ($this->gzip_compress_js === TRUE || $this->force_cache_js === TRUE) {
						$http_headers .= $this->_http_headers($type, $this->gzip_compress_js, $this->force_cache_js);
					}
					$this->js = $http_headers."\n".$this->js;
					file_put_contents($this->cache_dir_js.'/'.$this->cache_file_js[$group], $this->js);		
				}
			}
		}
		$output = '';
		if ($type == 'css') {
			foreach ($this->asset_groups[$type] as $group) {
				$output .= "<link rel=\"stylesheet\" href=\"".secure_base_url().ltrim($this->CI->config->item('alp_cache_dir_css'), "/").$this->cache_file_css[$group]."\" type=\"text/css\" media=\"".$group."\" />\n";
			}
		} else if ($type == 'js') {
			$output .= "<script src=\"".secure_base_url().ltrim($this->CI->config->item('alp_cache_dir_js'), "/").$this->cache_file_js[$group]."\" type=\"text/javascript\"></script>\n";
		}
		return $output;
	}
	
	/**
	* Load the config values of the CSS componente
	**/
	private function _load_css_config() {
		if ($this->csstidy_loaded) return;
		
		$this->enable_csstidy = $this->CI->config->item('alp_enable_csstidy');
		if ($this->enable_csstidy === TRUE) {
			$this->csstidy_loaded = TRUE;
			
			$csstidy = BASEPATH.$this->CI->config->item('alp_csstidy_basepath')."class.csstidy.php";
			if (file_exists($csstidy))
			   	require_once($csstidy);		
				
			if (class_exists('csstidy'))
				$this->csstidy = new csstidy();
			$this->csstidy->load_template($this->CI->config->item('alp_csstidy_template'));
			
			if ($this->enable_csstidy === TRUE)
				$csstidy_config = $this->CI->config->item('alp_csstidy_config');
				foreach ($csstidy_config as $key => $val)
					$this->csstidy->set_cfg($key, $val);
			
			$this->gzip_compress_css = $this->CI->config->item('alp_gzip_compress_css');  
		}
	}
	
	/**
	* Load the config values of the JS componente
	**/
	private function _load_js_config() {
		if ($this->jsmin_loaded) return;
		$this->enable_jsmin = $this->CI->config->item('alp_enable_jsmin');
		
		if ($this->enable_jsmin === TRUE) {
			$this->jsmin_loaded = TRUE;
			$jsmin = BASEPATH.$this->CI->config->item('alp_jsmin');
			if (file_exists($jsmin))
			   	require_once($jsmin);			
			$this->gzip_compress_js = $this->CI->config->item('alp_gzip_compress_js');
			$this->force_cache_js = $this->CI->config->item('alp_force_cache_js');	
		}
	}
	
	/**
	* Delete old cache files in a directory
	* @param	Path of directory
	* @param	Type of cache (either 'css' or 'js')
	* @param	String for matching file names
	**/
	private function _unlink_old_caches($cache_dir, $new_file) {
		if ( ! @is_dir($cache_dir))
		{
			return FALSE;
		}
		
		$new_file = explode('_',substr($new_file,0,strrpos($new_file,'.')));
		
		//Days after which files with no read accesses get deleted:
		$delete_unread_after = 1;
		
		if ($handle = opendir($cache_dir)) {
			while (FALSE !== ($file = readdir($handle)))
			{
				//Ignore items starting with '.'
				if (strncasecmp($file,'.',1))
				{
					//If it's a file and matches the criteria, then add it to the array
					if (is_file($cache_dir.'/'.$file))
					{
						if (strpos($file,(implode('_',array($new_file[0],$new_file[1],$new_file[2])))) === 0)
						{
							unlink($cache_dir.'/'.$file);
						} else {
							$days_in_seconds = $delete_unread_after * 60 * 60 * 24;
							if ((fileatime($cache_dir.'/'.$file) + $days_in_seconds) < time() ) {
								unlink($cache_dir.'/'.$file);
							}
						}
					}
				}
			}
			closedir($handle);
		}
	}
  	
  	/**
	* Prevents url() references fom inside CSS files from breaking.
	* @param	Name of group to fix files of
	**/	
	function _fix_css_urls($group) {
		foreach ($this->_assets['css'][$group] as $css_path => $code) {
			preg_match_all("/url\((?P<urls>.*?)\)/is", $code, $matches);
			if (!empty($matches['urls'])) {
				$urls = array();
				foreach ($matches['urls'] as $old_url) {
					$old_url = trim($old_url,'"\'');
					if (strlen($old_url[1]) > 7 && strcasecmp(substr($old_url[1], 0, 7), 'http://') == 0) {
						$new_url = $old_url;
					} else {
						$new_url = dirname($css_path).'/'.$old_url;
					}
					$urls[$old_url] = $this->relative_path_to(str_replace(dirname(FCPATH),'',trim($this->cache_dir_css)).'/', $new_url);
				}
				$this->_assets['css'][$group][$css_path] = str_replace(array_keys($urls), array_values($urls), $code);
			}				
		}
	}
	
	/**
	 * Relative Path
	 *
	 * Find relative path from one file to the other
	 *
	 * @access	public
	 * @param	string	the first URL (subject)
	 * @param	string	the first URL (object)
	 * @return	string
	 */	
	function relative_path_to($from_file, $to_file)
	{
		// function author: name unknown (http://raribusment.com)
		// license: "Creative Commons Attribution-Share Alike 2.0 Generic" (http://creativecommons.org/licenses/by-sa/2.0/)
		// original source: http://raribusment.com/coding/relativepath/
		
		// what separator are we using?
		// remember to check strpos with ===
		$separator = '/';
		$from_file = str_replace("\\", "/", $from_file);
		$to_file = str_replace("\\", "/", $to_file);
//		if ((strpos($from_file, $separator)===false) &&
//			(strpos($to_file,   $separator)===false)) {
//	
//			$separator = "\\"; // for windows systems is \
//		}
	
		// we split the paths in pieces
		$from_file_pieces = explode($separator, $from_file);
		$to_file_pieces   = explode($separator, $to_file);
	
		// pieces in common
		$i = 0;
		while (strcmp($from_file_pieces[$i], $to_file_pieces[$i]) == 0) {
			$i++;
		}
		$pieces_in_common = $i;
	
		// number of pieces each path
		$from_file_pieces_length = count($from_file_pieces);
		$to_file_pieces_length = count($to_file_pieces);
	
		// folders up/down. We substract one -> the last piece is a file, not a folder
		$folders_down = $from_file_pieces_length - $pieces_in_common - 1; // from -> root
		$folders_up = $to_file_pieces_length - $pieces_in_common -1;	// root -> to
	
		// folders down: from -> root
		$relative_path_folders_down = '';
		// hanging from the same folder 
		if ( $folders_down == 0 && $folders_up == 0 ) {
			// do nothing, $relative_path_folders_down is already null
		}
		// second file hangs in a subdirectory of the first file
		else if ( $folders_down == 0 && $folders_up != 0 ) {
			$relative_path_folders_down = $separator;
		}
		else {
			for ($i = 0; $i<$folders_down; $i++) {
	
				$relative_path_folders_down .= '..'.$separator;
			}
		}
	
		// folders up: root -> to
		$relative_path_folders_up = '';
		$to_last_folder = $pieces_in_common + $folders_up;
		for ($i = $pieces_in_common; $i<$to_last_folder; $i++) {
	
			$relative_path_folders_up .= $to_file_pieces[$i].$separator;
		}
		
		// we add the file
		$relative_path_folders_up .= $to_file_pieces[$to_last_folder];
		
		return $relative_path_folders_down.$relative_path_folders_up;
	}

	/**
	* Set the headers to be sent in the javascript and css files
	* @param	Type of asset file (either 'css' or 'js')
	* @param	Whether to gzip asset
	* @param	Whether to set a far-future caching expiration date to ensure optimal caching
	**/
	private function _http_headers($type = '', $gzip_compress = FALSE, $far_future_expire = TRUE) {
		if ($type === 'css')
			$mime_type = 'css';
		else if ($type === 'js')
			$mime_type = 'javascript';
		else
			return FALSE;

		$php_header = "";

		if ($gzip_compress) {
			$php_header = '<?php
// Gzip encode the contents of the output buffer.
function gzip_compress($output) {
$compressed_out = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
$compressed_out .= substr(gzcompress($output, 2), 0, -4);
if (strlen($output) >= 1000) {
	header("Content-Encoding: gzip");
	return $compressed_out;
} else {
	return $output;
}
}
if (strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip"))
	ob_start("gzip_compress");
?>';
		}
		if ($far_future_expire) {
			//When will the file expire?
			$offset = 6000000 * 60 ;
			$ExpStr = "Expires: " .
			gmdate("D, d M Y H:i:s",
			time() + $offset)." GMT";
			
			$php_header .= '<?php
header("Cache-Control: must-revalidate");
header("'.$ExpStr.'");
?>';
		}
		return $php_header;
	}
	
	/**
	* Get location of asset
	* @access  private
	* @param   string	the name of the file or asset relative to configured assets folder
	* @param   string	optional, module name
	* @param   string	the asset type (name of folder within module name)
	* @return  string	relative path to asset
	**/
	private function _asset_loc($asset_name, $module_name = NULL, $asset_type = NULL)
	{
		$asset_location = $this->asset_dir;//new config
		
		if(!empty($module_name)):
			$asset_location .= 'modules/'.$module_name.'/';
		endif;

		$asset_location .= $asset_name;
		return $asset_location;
	}
}

?>