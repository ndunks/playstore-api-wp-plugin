<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Playstore_API_Elementor_Widget_App_Info extends \Elementor\Widget_Base {
	public static $options = [
		'package' =>  'Package ID',
		'name' =>  'App Name',
		'developer_link' =>  'Developer Name',
		'developer_email' =>  'Developer Email',
		'developer_website' =>  'Developer Website',
		'version' =>  'Version',
		'version_code' =>  'Version Code',
		'upload_date' =>  'Upload Date',
		'downloads'  =>  'Download Count',
		'size'  =>  'Size',
		'type'		=>  'Type',
	];

	public function get_name() {
		return 'apk-app-info';
	}

	public function get_title() {
		return __( 'App Info' );
	}


	public function get_icon() {
		return 'fa fa-info';
	}

    public function get_categories() {
		return [ 'playstore-api' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_rating',
			[
				'label' => __( 'Display Item' ),
			]
		);
		$template = [
			'label' => '#LABEL',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_off' => __( 'Hide' ),
			'label_on' => __( 'Show' ),
			'label_block' => false,
			'default' => 'yes',
		];
		
		// Default option set as hide
		$default_hide_options = ['package','name', 'developer_email', 'developer_website', 'version_code'];
		foreach (self::$options as $key => $label) {
			$template['label'] = $label . " Visible";
			$template['default'] = in_array( $key, $default_hide_options ) ? 'no' : 'yes';
			$this->add_control(
				$key . '_label',
				[
					'label' => $label . " Label",
					'type' => \Elementor\Controls_Manager::TEXT,
					'input_type' => 'text',
					'placeholder' => $label,
				]
			);
			$this->add_control($key, $template);
			$this->add_control(
				$label . '_divider',
				[
					'type' => \Elementor\Controls_Manager::DIVIDER
				]
			);
			
		}

		$this->end_controls_section();

	}

	protected function render() {
		if(! Playstore_API::is_apk_post() ) {
			echo "<p><i>Not APK Post</i></p>";
			return;
		}

		$settings = $this->get_settings_for_display();
		echo '<table class="widget-app-info table table-sm table-bordered">';
		foreach (self::$options as $key => $label) {
			if(@$settings[$key] != 'yes') continue;
			if( !empty(@$settings[$key.'_label']) ){
				$label = $settings[$key . '_label'];
			}
			printf('<tr><th>%s</th><td>%s</td></tr>', $label, @Playstore_API::$var['apk_data'][$key] );

		}
		echo '</table>';
	}

}