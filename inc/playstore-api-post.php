<?php if(!defined('PLAYSTORE_API_URL')) die(); 
include_once Playstore_API::f('inc/function.php');

class Playstore_API_Post {

	var	$data		= null;
	var	$API		= null;
	static $VAR		= null;

	function __construct($data)
	{
		global $PLAYSTORE_API;
		if(!isset($PLAYSTORE_API)) throw new Exception("Playstore API Not intialized", 1);
		$this->API	=& $PLAYSTORE_API;
		
		// Add and formatting data
		
		if(!empty(@$data['size']))
			$data['file_size']	= playstore_api_format_size($data['size']);
		
		if(!empty(@$data['category'])){
			$category				= ucwords( strtolower(trim(strtr(str_replace(@$data['type'].'_', '', @$data['category']), '_', ' ' ))));
			$data['category_ori']	= $data['category'];
			$data['category']		= $category;
		}
		
		$data['type']	= ucwords(strtolower($data['type']));

		if(!empty(@$data['video'])){
			$match	= null;
			if(preg_match('/v=(.*?)&/', $data['video'], $match)){
				$data['youtube_id']	= $match[1];
			}
		}
		$this->data	= $data;

		//Intialize other variable helper
		if(empty(self::$VAR)){
			list($year, $month, $month_name, $month_name_long,
				$day, $hour, $minute, $second) = explode(' ', current_time('Y m M F d H i s'));
			$domain		= $_SERVER['SERVER_NAME'];
			$uid		= $this->API->UID;
			self::$VAR	= compact('domain','uid','year','month','month_name','month_name_long','day','hour','minute','second');
		}
	}

	function get_meta($preview = false)
	{
		$meta	= array();

		if($preview){
			$meta['apk_data']		= 'Array(Full APK Data)';
		}else{
			$meta['apk_data']		= &$this->data; // full apk data, keep reference!
		}
	
		$meta['apk_package']	= $this->package;
		$meta['apk_version']	= $this->version;
		$meta['apk_download']	= Playstore_API::$config['download_apk_file'];

		if(Playstore_API::$config['wpseo_support']){
			$meta['_yoast_wpseo_focuskw']	= $this->parse('wpseo_focus_keyword');
			$meta['_yoast_wpseo_metadesc']	= $this->parse('wpseo_description');
			$meta['_yoast_wpseo_title']		= $this->parse('wpseo_title');
		}
		return $meta;
	}

	function get_content($echo = false)
	{
		if(!$echo) ob_start();

		$data	=& $this->data;
		$API	=& $this->API;

		include $this->API->get_user_template();

		return $echo ? true : ob_get_clean();
	}

	function create_category()
	{
		$ids		= array();
		$parent_id	= wp_insert_category(array(
							'cat_name'		=> $this->type,
							'description'	=> $this->parse('parent_category_desc')
						), true);


		if(is_wp_error($parent_id)){
			if($parent_id->get_error_code() == 'term_exists')
				$ids[]	= $parent_id->get_error_data();
		}else $ids[]	= $parent_id;
		
		if(isset($this->category)){
			$cat_id		= wp_insert_category(array(
								'cat_name'			=> $this->category,
								'description'		=> $this->parse('category_desc'),
								'category_parent'	=> isset($ids[0]) ? $ids[0] : null
							), true);

			if(is_wp_error($cat_id)){
				if($cat_id->get_error_code() == 'term_exists')
					$ids[]	= $cat_id->get_error_data();
			}else $ids[]	= $cat_id;
		}

		return empty($ids) ? null :  $ids;
	}

	function parse_callback($match)
	{
		if( isset($this->data[ $match[1] ]) ){
			return $this->data[ $match[1] ];
		}elseif(isset(self::$VAR[ $match[1] ])){
			return self::$VAR[ $match[1] ];
		}else return $match[0];
	}

	function is_screenshot_downloaded()
	{
		return isset($this->data['screenshot_downloaded']);
	}
	
	function is_icon_downloaded()
	{
		return isset($this->data['icon_downloaded']);
	}

	function parse_str($str)	{ return empty($str) ? '' : preg_replace_callback('/{(.*?)}/', array($this,'parse_callback'), $str); }
	function parse($config_key)	{ return $this->parse_str(@Playstore_API::$config[$config_key]); }
	function get_apk_path()		{ return Playstore_API::$config['apk_dir'] . '/' .  $this->get_apk_sub_dir(); }
	function get_apk_sub_dir()	{ return $this->parse('apk_sub_dir'); }
	function get_apk_name()		{ return $this->parse('apk_file_name'); }
	function get_title()		{ return $this->parse('post_title'); }
	function get_slug()			{ return sanitize_title($this->parse('post_slug')); }
	function __isset($key)		{ return isset($this->data[$key]); }
	function __set($key, $value){ $this->data[$key]	= $value; }
	function __get($key)		{ return isset($this->data[$key]) ? $this->data[$key] : null; }
	function __unset($key)		{ unset($this->$data[$key]); }

}

