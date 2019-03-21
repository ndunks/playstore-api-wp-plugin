<?php
function playstore_api_elementor_widget_render($content, $widget){
    global $id;
    // Do shortcode even on preview
    if($widget->get_name() == 'heading'){
        //$content = shortcode_unautop( $content );
        $content = do_shortcode( $content );
    }
    return $content;
}
// Only activate on preview mode
if( isset($_GET['elementor-preview']) ||
    (isset($_GET['action']) && $_GET['action'] == elementor)
    ){
    add_filter( 'elementor/widget/render_content', 'playstore_api_elementor_widget_render', 99999, 2 );
}