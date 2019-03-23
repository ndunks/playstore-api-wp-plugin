<?php


function playstore_api_elementor_widget_render($content, $widget)
{
    //global $id;

    // Only post with apk meta data
    /* if(! Playstore_API::is_apk_post() ) {
        return $content;
    } */

    $type = $widget->get_name();

    $preview = isset($_GET['action']) && $_GET['action'] == 'elementor';

    // Stop here,bellow code just hook in preview mode
    if(!$preview){
        return $content;
    }
    // Do shortcode even on preview
    if($type == 'heading'){
        //$content = shortcode_unautop( $content );
        $content = do_shortcode( $content );
    }

    return $content;
}
function playstore_api_elementor_loaded(){

    // List Custom Widget, hardcoded to avoid other inclusion
    $widgets = ['Star_Rating', 'App_Info', 'Screenshot', 'Video'];
    foreach ($widgets as $value)
    {
        include Playstore_API::f('inc/elementor_widget/' . strtolower($value) . '.php');
        $class = "Playstore_API_Elementor_Widget_$value";

        \Elementor\Plugin::$instance->widgets_manager->register_widget_type(new $class());
    }
}

function playstore_api_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'playstore-api',
		[
			'title' => 'Playstore API',
			'icon' => 'fa fa-play',
		]
    );
}

add_filter( 'elementor/widget/render_content', 'playstore_api_elementor_widget_render', 99999, 2 );
//add_action( 'elementor/widget/before_render_content', 'playstore_api_elementor_widget_before_render', 10, 1 );
add_action( 'elementor/widgets/widgets_registered', 'playstore_api_elementor_loaded');
add_action( 'elementor/elements/categories_registered', 'playstore_api_elementor_widget_categories' );