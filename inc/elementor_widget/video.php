<?php
/**
 * Modified from Elementor Video Widget
 */
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Embed;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Playstore_API_Elementor_Widget_Video extends Elementor\Widget_Base {

	public function get_name() {
		return 'apk-video';
    }
    
	public function get_title() {
		return __( 'App Video' );
    }
    
	public function get_icon() {
		return 'eicon-youtube';
    }
    
	public function get_categories() {
		return [ 'playstore-api' ];
    }
    
	public function get_keywords() {
		return [ 'video', 'player', 'embed', 'youtube', 'vimeo', 'dailymotion' ];
    }
    
	protected function _register_controls() {
		$this->start_controls_section(
			'section_video',
			[
                'label' => __( 'Video' )
			]
        );
        $this->add_control('header',[
            'type' => Controls_Manager::RAW_HTML,
            'label' => 'Embed youtube video that provided in App Description. If you want to add custom video to post, use Elementor Video Widget instead.'
        ]);
        $this->add_control(
			'youtube_url',
			[
				'label' => __( 'Link' ),
                'type' => Controls_Manager::HIDDEN,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __( 'Enter your URL' ) . ' (YouTube)',
				'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
                'label_block' => true
			]
        );
        

		$this->add_control(
			'start',
			[
				'label' => __( 'Start Time' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Specify a start time (in seconds)' ),
				'condition' => [
					'loop' => '',
				],
			]
		);

		$this->add_control(
			'end',
			[
				'label' => __( 'End Time' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Specify an end time (in seconds)' )
			]
		);

		$this->add_control(
			'video_options',
			[
				'label' => __( 'Video Options' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => __( 'Mute' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'controls',
			[
				'label' => __( 'Player Controls' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide' ),
				'label_on' => __( 'Show' ),
				'default' => 'yes'
			]
        );
        
		$this->add_control(
			'modestbranding',
			[
				'label' => __( 'Modest Branding' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					/* 'video_type' => [ 'youtube' ], */
					'controls' => 'yes',
				],
			]
        );
        
		// YouTube.
		$this->add_control(
			'yt_privacy',
			[
				'label' => __( 'Privacy Mode' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.' ),
			]
		);

		$this->add_control(
			'rel',
			[
				'label' => __( 'Suggested Videos' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Current Video Channel' ),
					'yes' => __( 'Any Video' ),
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => __( 'Image Overlay' ),
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label' => __( 'Image Overlay' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide' ),
				'label_on' => __( 'Show' ),
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label' => __( 'Choose Image' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'lazy_load',
			[
				'label' => __( 'Lazy Load' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_image_overlay' => 'yes',
					/* 'video_type!' => 'hosted', */
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_overlay', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_play_icon',
			[
				'label' => __( 'Play Icon' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label' => __( 'Lightbox' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'label_off' => __( 'Off' ),
				'label_on' => __( 'On' ),
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => __( 'Video' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => __( 'Aspect Ratio' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
				],
				'default' => '169',
				'prefix_class' => 'elementor-aspect-ratio-',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-wrapper',
			]
		);

		$this->add_control(
			'play_icon_title',
			[
				'label' => __( 'Play Icon' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => __( 'Color' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => __( 'Size' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'play_icon_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Shadow', 'Text Shadow Control' ),
					],
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => __( 'Lightbox' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
					'lightbox' => 'yes',
				],
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label' => __( 'Background Color' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label' => __( 'UI Color' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color_hover',
			[
				'label' => __( 'UI Hover Color' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'lightbox_video_width',
			[
				'label' => __( 'Content Width' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_content_position',
			[
				'label' => __( 'Content Position' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'' => __( 'Center' ),
					'top' => __( 'Top' ),
				],
				'selectors' => [
					'#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
				],
				'selectors_dictionary' => [
					'top' => 'top: 60px',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_content_animation',
			[
				'label' => __( 'Entrance Animation' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
    }
    
	protected function render() {
		$settings = $this->get_settings_for_display();

        $video_url = $settings[ 'youtube_url' ];

		if ( empty( $video_url ) ) {
			return;
		}

        $embed_params = $this->get_embed_params();

        $embed_options = $this->get_embed_options();

        $video_html = Embed::get_embed_html( $video_url, $embed_params, $embed_options );

		if ( empty( $video_html ) ) {
			echo esc_url( $video_url );

			return;
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-wrapper' );

		if ( ! $settings['lightbox'] ) {
			$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-fit-aspect-ratio' );
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ) );
		?>
		<div <?php echo $this->get_render_attribute_string( 'video-wrapper' ); ?>>
			<?php
			if ( ! $settings['lightbox'] ) {
				echo $video_html; // XSS ok.
			}

			if ( $this->has_image_overlay() ) {
				$this->add_render_attribute( 'image-overlay', 'class', 'elementor-custom-embed-image-overlay' );

				if ( $settings['lightbox'] ) {
                    
					$lightbox_url = Embed::get_embed_url( $video_url, $embed_params, $embed_options );

					$lightbox_options = [
						'type' => 'video',
						'videoType' => 'youtube',
						youtube_ => $lightbox_url,
						'modalOptions' => [
							'id' => 'elementor-lightbox-' . $this->get_id(),
							'entranceAnimation' => $settings['lightbox_content_animation'],
							'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
							'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
							'videoAspectRatio' => $settings['aspect_ratio'],
						],
                    ];
                    
					$this->add_render_attribute( 'image-overlay', [
						'data-elementor-open-lightbox' => 'yes',
						'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
					] );

					if ( Plugin::$instance->editor->is_edit_mode() ) {
						$this->add_render_attribute( 'image-overlay', [
							'class' => 'elementor-clickable',
						] );
					}
				} else {
					$this->add_render_attribute( 'image-overlay', 'style', 'background-image: url(' . Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings ) . ');' );
				}
				?>
				<div <?php echo $this->get_render_attribute_string( 'image-overlay' ); ?>>
					<?php if ( $settings['lightbox'] ) : ?>
						<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_overlay' ); ?>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_play_icon'] ) : ?>
						<div class="elementor-custom-embed-play" role="button">
							<i class="eicon-play" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php echo __( 'Play Video' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			<?php } ?>
		</div>
		<?php
    }
    
	public function render_plain_content() {
        $settings = $this->get_settings_for_display();
        $url = $settings[ 'youtube_url' ];
		echo esc_url( $url );
    }
    
	public function get_embed_params() {
		$settings = $this->get_settings_for_display();

		$params = [];

		if ( $settings['autoplay'] && ! $this->has_image_overlay() ) {
			$params['autoplay'] = '1';
        }

        $params_dictionary = [
            'loop',
            'controls',
            'mute',
            'rel',
            'modestbranding',
        ];

        if ( $settings['loop'] ) {
            $video_properties = Embed::get_video_properties( $settings['youtube_url'] );

            $params['playlist'] = $video_properties['video_id'];
        }

        $params['start'] = $settings['start'];

        $params['end'] = $settings['end'];

        $params['wmode'] = 'opaque';

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			$setting_value = $settings[ $setting_name ] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
    }
    
	protected function has_image_overlay() {
		$settings = $this->get_settings_for_display();

		return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
	}

	private function get_embed_options() {
		$settings = $this->get_settings_for_display();

        $embed_options = [];
        $embed_options['privacy'] = $settings['yt_privacy'];
		$embed_options['lazy_load'] = ! empty( $settings['lazy_load'] );

		return $embed_options;
    }
    public function get_settings_for_display( $setting_key = null ){
		$settings = parent::get_settings_for_display( $setting_key );
		if( is_null( $setting_key ) ){
			$settings['youtube_url'] = $this->getYoutubeUrl();
		}elseif( $setting_key == 'youtube_url' ){
			$settings = $this->getYoutubeUrl();
		}
        return $settings;
    }
    private function getYoutubeUrl(){
		if(Playstore_API::is_apk_post()){
			if(isset(Playstore_API::$var['apk_data']['video'])){
				return Playstore_API::$var['apk_data']['video'];
            }
		}
		return '';
    }
}
