<?php if(!defined('PLAYSTORE_API_URL')) die();
ob_start();
include self::f('inc/default_download_page.html');
$html	=	ob_get_clean();
$post_id= false;
$post	= array();
$query	= new Wp_Query(array(
				'post_per_page' => 1,
				'post_type'     => 'page',
				'name'          => self::$config['download_page_slug'],
			));

if ( !empty($query->posts) ){
	$page		= $query->posts[0];
	$post['ID']	= $page->ID;
}

$post['post_title']		= "Download APK Redirect Page";
$post['post_name']		= self::$config['download_page_slug'];
$post['post_status']	= 'publish';
$post['post_type']		= 'page';
$post['post_content']	= $html;



if( wp_insert_post($post) ){
	//self::update_option('download_page_created', true);
	echo "<h2>Page Created</h2>";
	echo '<script type="text/javascript">document.location = "' . self::u() . '";</script>';
}else{
	echo "<h2>Fail creating Page with slug " . self::$config['download_page_slug'] . "</h2>";
}