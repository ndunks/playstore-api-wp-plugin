<?php

class Playstore_API_Elementor_Widget_Screenshot extends \Elementor\Widget_Image_Carousel {
	/* function __construct(){
		parent::__construct();
		var_dump( "WTYPE", $this->get_data( 'widgetType' ));exit;
	} */
    public function get_name(){
        return 'apk-screenshot';
    }

    public function get_title(){
        return __( 'App Screenshots' );
	}

	/* protected function get_default_data(){

		$data = parent::get_default_data();

		// Threat behaviour like an image-carousel Widget
		// But it will like image-carousel! we lose the custumization
		$data['widgetType'] = 'image-carousel';

		return $data;
	} */
    public function get_categories() {
		return [ 'playstore-api' ];
    }
    protected function _register_controls(){
		parent::_register_controls();
		$this->update_control('section_image_carousel', ['label' => __('Screenshot Carousel')]);
		$this->remove_control('carousel');
	}
	
    public function get_raw_data( $with_html_content = false ) {
		$raw_data = parent::get_raw_data( $with_html_content );
		$raw_data['settings']['carousel'] = $this->loadScreenshot();
        return $raw_data;
	}
	/* protected function _get_initial_config() {
		$config = parent::_get_initial_config();

		$config['widget_type'] = 'image-carousel';
		return $config;
	} */

	/* protected function get_html_wrapper_class() {
		return 'elementor-widget-image-carousel';
	} */
	/* protected function render(){
		// $this->add_render_attribute( '_wrapper', 'class', 'elementor-widget-image-carousel' );
		$settings = $this->get_settings();

		$this->add_render_attribute( '_wrapper', 'data-widget_type', 'image-carousel.' . ( ! empty( $settings['_skin'] ) ? $settings['_skin'] : 'default' ), true );
		parent::render();
	} */
	/* protected function _add_render_attributes() {
		parent::_add_render_attributes();

		$settings = $this->get_settings();

		$this->add_render_attribute( '_wrapper', 'data-widget_type', 'image-carousel.' . ( ! empty( $settings['_skin'] ) ? $settings['_skin'] : 'default' ), true );
	} */
	
    public function get_settings_for_display( $setting_key = null ){
		$settings = parent::get_settings_for_display( $setting_key );
		if( is_null( $setting_key ) ){
			$settings['carousel'] = $this->loadScreenshot();
		}elseif( $setting_key == 'carousel' ){
			$settings = $this->loadScreenshot();
		}
        return $settings;
	}
	private function loadScreenshot(){
		$settings = [];
		if(Playstore_API::is_apk_post()){
			if(isset(Playstore_API::$var['apk_data']['screenshot_downloaded'])){
				$settings = Playstore_API::$var['apk_data']['screenshot_downloaded'];
			}else{
				foreach (Playstore_API::$var['apk_data']['screenshot'] as $url) {
					$settings[] = ['url' => $url ];
				}
			}
		}
		return $settings;
	}
	
}