<?php
/*
	Plugin Name: Playstore API
	Version: 1.2
	Description: Playstore API (Unofficial) Plugin for wordpress. Search, get app detail, and download APK to your own hosting. This plugin require API Token from playstore-api.com.
	Plugin URI: http://playstore-api.com/
	Author: playstore-api.com
	Author URI: playstore-api.com
 */
/**** Changelog 
1.0 - 2016-09-09
	- Build first beta version
	
1.1 - 2017-09-06
	- [view/post.php] Fix download error (Updated API Backend)

1.2 - 2019-04-21
	- Update bootstrap 4
	- Elementor donwload landing page support
	- Default template using bootstrap carousel
	- Add playstore button on admin bar for quick access

****/


define('PLAYSTORE_API_DIR'		, plugin_dir_path( __FILE__ ) );
define('PLAYSTORE_API_URL'		, plugins_url('', __FILE__) . '/');
define('PLAYSTORE_API_DEBUG'	, false	);

//include_once PLAYSTORE_API_DIR . 'playstore-apifunction.php';
//error_log(E_ALL & ~E_NOTICE);


class Playstore_API {

	//VAR		$config	= array();
	var		$error	= false;
	var		$package= null;
	var		$data	= null;
	var		$UID	= 0;
	var		$link_data	= array();

	static	$title	= 'Playstore API';
	static	$name	= 'playstore_api';
	static	$version= '1.2';
	static	$me		= false;
	static	$config	= null;
	static	$var	= array(); // variable scoope for used in plugin code
	static	$DEMO	= false;
	static	$has_elementor = false;

	function __construct()
	{
		self::$me	=& $this;

		register_activation_hook(__FILE__	, array($this, 'activate'));
		register_deactivation_hook(__FILE__	, array($this, 'deactivate'));

		add_action('init'					, array($this, 'init'));
		add_action('admin_menu'				, array($this, 'menu'));
		add_action('admin_bar_menu'			, array($this, 'admin_bar_menu'), 90);
		add_action('wp_print_scripts'		, array($this, 'javascripts'));
		add_action('wp_print_styles'		, array($this, 'stylesheet'));

		add_action('admin_print_scripts'	, array($this, 'javascripts'));
		add_action('admin_print_styles'		, array($this, 'stylesheet_backend'));

		add_action('load-post.php'			, array($this, 'post_load'));
		add_filter('tiny_mce_before_init'	, array($this, 'tiny_mce'), 999 );
		add_action('wp_ajax_playstore_api'	, array($this, 'ajax'));
		
		if(is_admin()) include self::f('admin.php');

		//intialize default config
		$saved_config = get_option(self::$name);
		if( !empty( $saved_config ) && is_array($saved_config) )
			self::$config	= $saved_config;
		else self::$config	= self::default_config();
		foreach (get_option('active_plugins') as $key => $value) {
			if(strpos($value, 'elementor/') >= 0){
				include Playstore_API::f('inc/elementor.php');
				self::$has_elementor = true;
				break;
			}
		}

	}
	
	function init()
	{

		wp_register_script('bootstrap'				, PLAYSTORE_API_URL . 'res/js/bootstrap.js', ['jquery' ], self::$version, false);
		wp_register_script('playstore-api'			, PLAYSTORE_API_URL . 'res/js/playstore-api.js', ['jquery' ], self::$version, true);
		wp_register_script('playstore-api-elementor', PLAYSTORE_API_URL . 'res/js/elementor.js', ['jquery', 'elementor-frontend'], self::$version, true);
		wp_register_style('bootstrap'				, PLAYSTORE_API_URL . 'res/css/bootstrap.css');
		wp_register_style('font-awesome'			, PLAYSTORE_API_URL . 'res/css/font-awesome.css');
		wp_register_style('playstore-api'			, PLAYSTORE_API_URL . 'res/css/style.css');
		wp_register_style('playstore-api-admin'		, PLAYSTORE_API_URL . 'res/css/admin.css');
		$this->UID = get_current_user_id();

		self::default_config();

		if( !isset(self::$config['download_page_created']) && self::$config['use_landing_page'] )
		{
			$page_check = get_posts(array(
							'name'      => self::$config['download_page_slug'],
							'post_type' => 'page'));

			if(!$page_check){
				add_action( 'admin_notices', array($this, 'notify_download_page') );
			}else{
				self::update_option('download_page_created', true);
			}
		}
		if(!self::$config['disable_shortcode_all']){
			add_shortcode('apk', array($this, 'shortcode'));
			add_shortcode('playstore_api_get_download_url', array($this, 'shortcode_download_url'));
			add_shortcode('playstore_api_timer_button', array($this, 'shortcode_timer_button'));
		}
		if(self::$config['use_landing_page'])
			add_filter( 'the_title', array($this,'download_page_title'));
		
		
	}

	function notify_download_page()
	{
		printf('<div class="update-nag notice"><p><b>%s :</b> %s <a href="%s">%s</a>, %s <code>%s</code></p></div>',
			self::$title,
			self::_('It looks like you have not setup download landing page, would you like to create default page?'),
			self::v('create_default_page'),
			self::_('Click Here'),
			self::_('the new page will use this slug:'),
			self::$config['download_page_slug']
		);
	}

	function javascripts()
	{
		if( ! self::$config['disable_bootstrap_js'] ){
			wp_enqueue_script('bootstrap');
		}

		wp_enqueue_script('playstore-api' );
		
		if( self::$has_elementor )
		{
			wp_enqueue_script('playstore-api-elementor');
		}

		if(is_page(self::$config['download_page_slug'])){
			$apk_url = $this->shortcode_download_url(array('in_download_page' => 1), null, null);
			$wait = intval(self::$config['download_wait']);
			if( $wait <= 0 ){
				$wait = 3;
			}
			echo "<script type='text/javascript'>" ;
			echo "var PLAYSTORE_API_APK_URL = " . json_encode($apk_url) . ";";
			echo "var PLAYSTORE_API_DOWNLOAD_WAIT = $wait;";
			echo "</script>\n";
		}
	}

	function stylesheet($backend = false)
	{
		if( ! self::$config['disable_bootstrap_css'] ){
			wp_enqueue_style('bootstrap');
		}
		wp_enqueue_style('font-awesome');
		wp_enqueue_style('playstore-api');
		if($backend){
			wp_enqueue_style('playstore-api-admin');
			if(!self::$config['disable_css_on_editor']){
				add_editor_style( PLAYSTORE_API_URL . 'res/css/style.css' );
				add_editor_style( PLAYSTORE_API_URL . 'res/css/bootstrap.css' );
			}
		}
	}
	
	function stylesheet_backend() { $this->stylesheet(true); }

	function tiny_mce($in)
	{
		$elements	= '+@[content|itemprop|itemscope|itemtype],meta[*],div[*],td[*],span[*],b[*],i[*],a[*]';
		$customs	= '~meta';
		$childs		= '+div/body/p[meta]';
		if ( isset( $in['extended_valid_elements'] ) ) {
			$in['extended_valid_elements'] .= ',' . $elements;
		} else {
			$in['extended_valid_elements'] = $elements;
		}
		if ( isset( $in['valid_children'] ) ) {
			$in['valid_children'] .= ',' . $childs;
		} else {
			$in['valid_children'] = $childs;
		}
		if ( isset( $in['custom_elements'] ) ) {
			$in['custom_elements'] .= ',' . $customs;
		} else {
			$in['custom_elements'] = $customs;
		}
		return $in;
	}

	function shortcode($args, $content, $tag){

		global $id;
		
		if(!is_array($args) || empty($args)) return '';

		if(!isset(self::$var['apk_data']) && !$this->get_meta_apk_data($id) )
			return '';

		$data	=& self::$var['apk_data'];
		$call_function = null;
		while (!empty($args)){
			$key	= array_shift($args);
			if($key[0] == ':'){
				//Warning Command Injection :-)
				$call_function = substr($key, 1);
				continue;
			}

			if(!isset($data[$key]))
				return '';
			else
				$data	=& $data[$key];
		}
		// syntax :function(*,args...)
		if($call_function !== null){
			$args	= explode('(', $call_function, 2);
			if( count($args) > 1 ){
				$call_function = array_shift($args);
				$args	= explode(',', rtrim($args[0], ')'));
				foreach ($args as $k => $v) {
					if( trim($v) === '*' ){
						$args[$k] = $data;
						break;
					}
				}
			}else{
				$args = [$data];
			}
			$data = call_user_func_array($call_function, $args);
		}
		return is_array($data) ? json_encode($data, JSON_PRETTY_PRINT) : strval($data);
	}

	function shortcode_timer_button($arg, $conteng, $tag){
		return <<<end
<div id="playstore_api_link" style="text-align: center">
	<i class="fa fa-spin fa-spinner"></i>
</div>
end;
	}

	function shortcode_download_url($arg, $content, $tag)
	{
		global $id;
		$query_apk_id = null;

		if(isset($_GET['apk_id'])){
			$query_apk_id = $_GET['apk_id'];
		}elseif(stripos($_SERVER['REQUEST_URI'], 'apk_id')){
			// Try find directory from URL!
			$query = [];
			parse_str( parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY), $query );
			if(isset($query['apk_id'])){
				$query_apk_id = $query['apk_id'];
			}

		}

		$post_id	= isset($query_apk_id) ? intval($query_apk_id) : $id;
		$download	= get_metadata('post', $post_id, 'apk_download', true);
		if($download == 'no') return '/NO_DOWNLOAD_URL';

		$is_download_page	= isset($arg['in_download_page']);

		if(!isset(self::$var['apk_data']) && !$this->get_meta_apk_data($post_id) )
			return '';
		if( self::$config['use_landing_page'] && !$is_download_page )
		{
			return get_home_url(null,self::$config['download_page_slug'] . '?apk_id=' . $post_id);
		}


		$url	= '/no-download-url-';

		if($download == 'yes')
		{
			$url	= self::$var['apk_data']['download_url'];
		}elseif($download == 'link')
		{
			$apk_name		= self::$var['apk_data']['file_name'];
			$package		= self::$var['apk_data']['package'];
			$data			= self::query('download', array('id' => $package, 'name' => $apk_name));
			$url			= $data['url'];
			if(empty($url)){
				$url	= '/failed_get_download_url';
			}
		}

		return $url;
	}
	function download_page_title($title, $post_id = null)
	{
		global $id, $post;
		if(!isset($_GET['apk_id']) || empty($id) || empty($post) || $post->post_name != self::$config['download_page_slug'])
			return $title;

		$apk_id	= intval($_GET['apk_id']);

		if(!isset(self::$var['apk_data']) && !$this->get_meta_apk_data($apk_id) )
			return $title;

		return 'Download ' . self::$var['apk_data']['name'] . ' version ' . self::$var['apk_data']['version'];
	}

	function metabox()
	{
		if(!self::$config['disable_shortcode'])
			add_meta_box( self::$name . '_shortcode', self::$title . ': Shortcode Helper ', array($this, 'metabox_shortcode'), 'post', 'normal', 'high' );
	}

	function metabox_shortcode(){ include self::f('view/metabox_shortcode.php'); }

	function post_load()
	{
		if(!isset($_GET['post']) || !is_numeric($_GET['post']))
			return;

		if($this->get_meta_apk_data($_GET['post']))
			add_action( 'add_meta_boxes', array($this, 'metabox' ) );
	}

	function get_meta_apk_data($post_id)
	{
		$apk_data	= get_post_meta($post_id, 'apk_data', true);

		if( empty($apk_data) )
		{
			self::$var['apk_data'] = array();
			return false;
		}else{
			self::$var['apk_data']	= $apk_data;
			return true;
		}
	}

	function get_user_template()
	{
		$user_template	= self::f("data/template_{$this->UID}.php");
		if(is_file($user_template)){
			return $user_template;
		}else{	// Default template
			return self::f("template.php");
		}

	}

	function set_user_template($html) {
		return file_put_contents(self::f("data/template_{$this->UID}.php"), $html);
	}
	
	function delete_user_template() {
		return unlink(self::f("data/template_{$this->UID}.php"));
	}

	function menu()
	{
		add_menu_page( 'Playstore API', 'Playstore API', 'publish_posts', self::$name, array($this, 'main'),'none');
	}
	
	function admin_bar_menu($wp_admin_bar)
	{
		
		$args = array(
			'id' => 'playstore-api-button',
			'title' => '<span>Playstore</span>',
			'href' => get_admin_url( get_current_blog_id(), 'admin.php?page=' . self::$name)
		);
		
		$wp_admin_bar->add_menu($args);
	}
	

	//View Router
	function main()
	{
		if(!empty(@$_GET['view'])){
			$view	= self::f('view/' . strtr($_GET['view'], "/\\'\"%.;:*\0", '----------') . '.php' );
			include is_file($view) ? $view : self::f('view/main.php');
		}else{
			include self::f('view/main.php');
		}
	}

	function activate()
	{
		$config	=& self::$config;
		
		if(!is_dir($config['data_path']) && !mkdir($config['data_path']) ){
			throw new Exception("Cannot create data directory {$config['data_path']}. You can create it manually to avoid this error.");
		}
		chmod($config['data_path'], 0777);
		$can_write = file_put_contents($config['data_path'] . 'index.html', date('Y-m-d h:i:s') . "<br/>\n", FILE_APPEND);

		if(!$can_write){
			throw new Exception("Data directory {$config['data_path']} is not WRITABLE. Please make it writable (chmod 777).");
		}
		// Create cache directory
		if(!is_dir($config['cache_dir']) && !mkdir($config['cache_dir']) ){
			throw new Exception("Cannot create cache directory {$config['cache_dir']}. You can create it manually to avoid this error.");
		}
		chmod($config['cache_dir'], 0777);
		// check for wpseo support
		$options	= get_option('active_plugins');
		foreach ($options as &$value){
			if(strpos($value, 'wordpress-seo/') !== false){
				$config['wpseo_support']	= true;
				break;
			}
		}
		return self::update_option();
	}

	function deactivate()
	{
		delete_option(self::$name);
	}

	/**
	 * Check an get the apk_data
	 */
	static function is_apk_post(){
		global $id;
		if(!empty(@self::$var['apk_data'])){
			return true;
		}else{
			return self::$me->get_meta_apk_data($id);
		}
	}

	static function update_option($new_key = null, $new_val = null)
	{
		if(!is_null($new_key))
			self::$config[$new_key]	= $new_val;
		return update_option(self::$name, self::$config);
	}
	
	static function get_app($package)
	{
		$data	= Playstore_API::query( 'detail', array('id' => $package ));
		$data['rating_simple'] = number_format(floatval(@$data['rating']['star_rating']), 1);
		$data['developer_url'] = 'https://play.google.com/store/apps/developer?id=' . urlencode($data['developer_name']);
		$data['developer_link'] = sprintf('<a href="%s" rel="nofollow" target="_blank">%s</a>', $data['developer_url'], esc_html($data['developer_name']) );
		require_once self::f('inc/playstore-api-post.php');
		return new Playstore_API_Post($data);
	}

	static function query($action = '', Array $param = array() )
	{
		if(is_string($action))
			$param['action']	= $action;
		else
			$param = array_combine($action, $param);

		$param['token']	= self::$config['api_token'];

		$url	= self::$config['api_server'] . '?' . http_build_query($param);
		$cache_file	= null;
		if(self::$config['cache_api']){
			$cache_file	= self::$config['cache_dir'] . 'cache_' . md5($url);
			if(	is_file($cache_file)
				&& filesize($cache_file) > 10
				&& time() - filemtime($cache_file) <= self::$config['cache_api']){
				$data	= unserialize(file_get_contents($cache_file));
				return $data;
			}
		}
		$opt	= array();
		$opt[CURLOPT_SSL_VERIFYHOST	]	= 0;
		$opt[CURLOPT_SSL_VERIFYPEER	]	= false;
		$opt[CURLOPT_ENCODING]  		= 'gzip';
		$opt[CURLOPT_RETURNTRANSFER]	= 1;
		$ch		= curl_init($url);
		curl_setopt_array($ch, $opt);
		$html	= curl_exec($ch);
		
		$http_info	= curl_getinfo($ch);
		curl_close($ch);
		if($http_info['http_code'] == '200'){
			$response = json_decode( $html, true );
			if($response['status'] == 'ERR'){
				throw new Exception($response['data'], 1);
			}else{
				if(empty($response['data']))
				{
					throw new Exception(self::_("Empty response from API server") . ": <br/>\n $html");
				}elseif($cache_file){
					file_put_contents($cache_file, serialize($response['data']));
				}
				return $response['data'];
			}
		}else{
			throw new Exception(self::_("Unknown response from API server") . ' http: ' . $http_info['http_code'], $http_info['http_code']);
			return array();
		} 
	}

	static function default_config($structured = false)
	{
		include self::f('config.php');
		if($structured)
			return $config;

		$filtered	= array();
		foreach ($config as &$value){
			foreach ($value['fields'] as $key => $input){
				if(isset($input['default']))
					$filtered[$key]	= $input['default'];
			}
		}
		return $filtered;

	}
	static function _($str){ return __($str, self::$name); }
	static function e($str){ return _e($str, self::$name); }
	static function v($str){ return self::u("view=$str"); }
	static function f($str){ return PLAYSTORE_API_DIR . $str; }
	static function u($str = null){ return admin_url('admin.php?page=' . self::$name . ($str ? "&$str" : '')); }
	static function run(){ return self::$me ? self::$me : new Playstore_API(); }
}

$PLAYSTORE_API	= Playstore_API::run();
