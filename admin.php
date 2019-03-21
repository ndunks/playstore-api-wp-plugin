<?php if(!defined('PLAYSTORE_API_URL')) die();

class Playstore_API_Admin
{
	static $me;
	static $name	= null;
	static $sections= null;

	function __construct()
	{
		self::$me		=& $this;
		self::$name		= Playstore_API::$name . '_admin';

		add_action('admin_menu', array($this, 'menu') );
		add_action('admin_init', array($this, 'init') );

		self::$sections	= Playstore_API::default_config(true);
	}

	function init()
	{
		foreach (self::$sections as $name => &$section){

			add_settings_section( $name, $section['title'], array($this,'section'), self::$name );

			foreach ($section['fields'] as $key => &$input){
				add_settings_field(
					$key,
					ucwords(strtr($key, '_-', '  ')),
					array($this,'field'),
					self::$name,
					$name,
					array($name, $key)
				);
			}
		}

		register_setting( self::$name, Playstore_API::$name, array($this, 'save') );

		// Check for Elementor
		if(class_exists('Elementor\Utils')){    
			include Playstore_API::f('inc/elementor.php');
		}
		
	}

	function save($config)
	{
		if ( !current_user_can('manage_options') ){
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		if(isset($config['reset'])){
			$config	= Playstore_API::default_config();
			add_settings_error(self::$name, 'notice_reset', __('Default settings applied.'), 'updated');
			return $config;
		}else{
			foreach (self::$sections as &$value){
				foreach ($value['fields'] as $key => $input){
					if($input['type'] == 'checkbox')
						$config[$key]	= isset($config[$key]);			
				}
			}
			$config['cache_api']	= intval($config['cache_api']);
			return $config;
		}
	}

	function menu()
	{
		add_options_page( Playstore_API::$title . ' Settings', Playstore_API::$title, 'manage_options', self::$name, array($this, 'page') );
	}

	function page()
	{
		if ( !current_user_can('manage_options') ){
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div clas="wp-playstore">
				<form method="post" action="options.php">
				<?php
					printf('<h1><img src="%s"/> %s Settings</h1>',
						PLAYSTORE_API_URL . 'res/img/logo.gif',
						Playstore_API::$title . ' ' . Playstore_API::$version
					);
					settings_fields( self::$name );
					do_settings_sections( self::$name );
				?>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
					<input onclick="return confirm('You will lost your API Token. Are you sure?')" type="submit" name="<?php echo Playstore_API::$name ?>[reset]" id="reset" class="button button-secondary" value="Reset Default"/>
				</p>
			</form>
		</div>
		<?php
	}

	function section($args)
	{
		if(!isset(self::$sections[ $args['id'] ]['desc']))
			return;

		printf('<div class="text-muted">%s</div>', self::$sections[ $args['id'] ]['desc'] );
	}

	function field($args)
	{
		list($name, $key)	= $args;
		$field		=& self::$sections[$name]['fields'][$key];
		$attr		= sprintf('id="%1$s" name="%2$s[%1$s]"', $key, Playstore_API::$name);
		$function	= 'field_' . $field['type'];

		$this->$function($field, Playstore_API::$config[ $key ], $attr);

		if(isset($field['info']))
			printf('<p class="description">%s</p>', $field['info']);
	}

	function field_text(&$field, $value = '', $attr = '')
	{
		$class	= isset($field['class']) ? $field['class'] : 'regular-text';
		printf('<input type="text" class="%s" value="%s" %s/>', $class, $value, $attr );
	}

	function field_textarea(&$field, $value = '', $attr = '')
	{
		$class	= isset($field['class']) ? $field['class'] : 'regular-text';
		$rows	= isset($field['rows']) ? $field['rows'] : '3';
		printf('<textarea class="%s" rows="%s" %s>%s</textarea>', $class, $rows, $attr, $value );
	}

	function field_checkbox(&$field, $value = '', $attr = '')
	{
		printf('<input type="checkbox" value="1" %s %s/>', checked(true, $value, false), $attr );
	}

	function field_select(&$field, $value = '', $attr = '')
	{
		$class	= isset($field['class']) ? $field['class'] : 'regular-text';
		printf('<select class="%s" %s/>', $class, $attr );
		foreach ($field['option'] as $key => $val)
		{
			$s	= $key == $value ? 'selected="1"' : '';
			printf('<option value="%s" %s>%s</option>', $key, $s, $val);
		}
		echo '</select>';
	}

	static function run(){ return self::$me ? self::$me : new Playstore_API_Admin(); }
}

Playstore_API_Admin::run();