<?php

class Playstore_API_Elementor_Widget_Star_Rating extends \Elementor\Widget_Star_Rating {

    public function get_name() {
        return 'apk-star-rating';
    }

    public function get_title() {
        return __( 'App Star Rating' );
    }
    public function get_categories() {
		return [ 'playstore-api' ];
    }
    protected function _register_controls(){
        parent::_register_controls();
        $this->remove_control('rating_scale');
        $this->remove_control('rating');
    }
    /* public function get_raw_data( $with_html_content = false ) {
        $raw_data = parent::get_raw_data( $with_html_content );
        $raw_data['settings']['rating'] = 4.8;//floatval(do_shortcode( '[apk rating star_rating]', true ));
        $raw_data['settings']['rating_scale'] = 5;
        return $raw_data;
    }
    public function get_settings_for_display( $setting_key = null ){
        $settings = parent::get_settings_for_display( $setting_key );
        $settings['rating'] = 2.5;//floatval(do_shortcode( '[apk rating star_rating]', true ));
        return $settings;
    } */
    protected function get_rating() {
        global $id;
        if(! Playstore_API::is_apk_post() ) {
            return [0, 5];
        }
        $rating = floatval(Playstore_API::$var['apk_data']['rating']['star_rating']);
        return [number_format($rating,1), 5];
    }
    protected function render() {
		$settings = $this->get_settings_for_display();
		$rating_data = $this->get_rating();
		$textual_rating = $rating_data[0] . '/' . $rating_data[1];
        $icon = '&#61445;';


		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#61446;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$this->add_render_attribute( 'icon_wrapper', [
			'class' => 'elementor-star-rating',
			'title' => $textual_rating,
			'itemtype' => 'http://schema.org/Rating',
			'itemscope' => '',
			'itemprop' => 'reviewRating',
		] );

		$schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
		$stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_stars( $icon ) . ' ' . $schema_rating . '</div>';
		?>

		<div class="elementor-apk-star-rating__wrapper">
            <?php if ( ! empty( $settings['title'] ) ) :
            if(strpos($settings['title'], '[') >= 0){
                $settings['title'] = do_shortcode( $settings['title'] );
            }
                ?>
				<div class="h3 d-block"><?php echo $settings['title']; ?></div>
			<?php endif; ?>
			<?php echo $stars_element; ?>
		</div>
		<?php
    }
    protected function _content_template() {
		?>
		<#
			var getRating = function() {
				var ratingScale = parseInt( settings.rating_scale, 10 ),
					rating = settings.rating > ratingScale ? ratingScale : settings.rating;
				return [ 3, 5 ];
			},
			ratingData = getRating(),
			rating = ratingData[0],
			textualRating = ratingData[0] + '/' + ratingData[1],
			renderStars = function( icon ) {
				var starsHtml = '',
					flooredRating = Math.floor( rating );

				for ( var stars = 1; stars <= ratingData[1]; stars++ ) {
					if ( stars <= flooredRating  ) {
						starsHtml += '<i class="elementor-star-full">' + icon + '</i>';
					} else if ( flooredRating + 1 === stars && rating !== flooredRating ) {
						starsHtml += '<i class="elementor-star-' + ( rating - flooredRating ).toFixed( 1 ) * 10 + '">' + icon + '</i>';
					} else {
						starsHtml += '<i class="elementor-star-empty">' + icon + '</i>';
					}
				}

				return starsHtml;
			},
			icon = '&#61445;';

			if ( 'star_fontawesome' === settings.star_style ) {
				if ( 'outline' === settings.unmarked_star_style ) {
					icon = '&#61446;';
				}
			} else if ( 'star_unicode' === settings.star_style ) {
				icon = '&#9733;';

				if ( 'outline' === settings.unmarked_star_style ) {
					icon = '&#9734;';
				}
			}

			view.addRenderAttribute( 'iconWrapper', 'class', 'elementor-star-rating' );
			view.addRenderAttribute( 'iconWrapper', 'itemtype', 'http://schema.org/Rating' );
			view.addRenderAttribute( 'iconWrapper', 'title', textualRating );
			view.addRenderAttribute( 'iconWrapper', 'itemscope', '' );
			view.addRenderAttribute( 'iconWrapper', 'itemprop', 'reviewRating' );

			var stars = renderStars( icon );
		#>

		<div class="elementor-apk-star-rating__wrapper">
			<# if ( ! _.isEmpty( settings.title ) ) { #>
				<div class="h3 d-block text-center">{{ settings.title }}</div>
			<# } #>
			<div {{{ view.getRenderAttributeString( 'iconWrapper' ) }}} >
				{{{ stars }}}
				<span itemprop="ratingValue" class="elementor-screen-only">{{ textualRating }}</span>
			</div>
		</div>

		<?php
	}
}