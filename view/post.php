<?php if(!defined('PLAYSTORE_API_URL')) die();
flush();
if(empty(@$_REQUEST['id'])) return;

try {
	$post	= self::get_app($_REQUEST['id']);
}catch (Exception $e){
	echo '<div class="alert alert-danger">ERROR:<br/>' . $e->getMessage() . '</div>';
	return;
}
$post_status			= isset($_GET['publish']) ? 'publish' : 'draft';
$insert					= array();
$insert['post_status' ]	= $post_status;
$insert['post_title'  ]	= $post->get_title();
$insert['post_name'   ]	= $post->get_slug();
$insert['post_content']	= $post->get_content();
$insert['meta_input'  ]	= $post->get_meta();

//default no download
$apk_path	= $post->get_apk_path();
$apk_name	= $post->get_apk_name();
$insert['meta_input']['apk_data']['file_name']	= $apk_name;
if(self::$config['download_apk_file'] == 'yes')
{
	echo '<h2>' . self::_('Downloading APK') . '</h2>';

	$apk_file	= wp_normalize_path($apk_path . '/' . strtr($apk_name, '|', '-'));
	$apk_temp	= $apk_file.'.progress';

	if(file_exists($apk_file)){
		if(filesize($apk_file) > 1024){
			printf(self::_('File exists %s, Skiping download..') . " <br/>\n", $apk_file);
			goto skip_download;
		}else unlink($apk_file);
	}

	if(!is_dir($apk_path) && !mkdir($apk_path, 0777, true)){
		echo "[ERROR] " . self::_('Cannot creating directory to storing APK file') . " : $apk_path";
		return;
	}

	self::e('Getting download URL..');
	try {
		$data			= self::query('download', array('id' => $post->package, 'name' => $apk_name, 'version_code' => $post->version_code));
		$download_url	= $data['url'];
		if(empty($download_url)){
			echo self::_('FAILED TO GET DOWNLOAD URL') . '<br/>';
			var_dump($data);
			return;
		}
		echo "OK<br/>\n";
	}catch (Exception $e){
		self::e('FAILED TO GET DOWNLOAD URL');
		echo "<br/>" . $e->getMessage();
		return;
	}

	echo self::_("Downloading") . " $apk_name.. ";
	flush();
	set_time_limit(0);
	$opt	= array();
	if(file_exists($apk_temp))
	{
		self::e('[resuming]');
		$opt[CURLOPT_HTTPHEADER]	= array('Range: bytes=' . filesize($apk_temp) . '-');
	}
	$fp		= fopen($apk_temp, 'a'); // save to
	if(!$fp){
		echo self::_('FAILED CREATING FILE') . " : $apk_temp<br/>\n";
		return;
	}
	$opt[CURLOPT_FILE]			=& $fp;
	$opt[CURLOPT_FOLLOWLOCATION]= true;
	
	$ch		= curl_init($download_url);
	curl_setopt_array($ch, $opt);
	if(! curl_exec($ch) ){
		$logs	= curl_getinfo($ch);
		$logs	= json_encode($logs, JSON_PRETTY_PRINT);
		//file_put_contents($apk_file.'.log', json_encode($logs, JSON_PRETTY_PRINT));
		echo self::_('FAILED TO DOWNLOAD APK') . '<br/> URL: ' . $download_url . '<br/>';
		echo "<pre>$logs</pre><br/>";
		return;

	}
	curl_close($ch);
	fclose($fp);
	rename($apk_temp, $apk_file);
	echo " OK<br/>\n";
	skip_download:
	$apk_url	= get_home_url(null, substr($apk_file, strlen(ABSPATH)));
	$insert['meta_input']['apk_data']['download_url']	= $apk_url;
}
echo '<h2>' . self::_('Creating Post') . '</h2>';
if( self::$config['autoset_post_category'] )
{
	self::e("Seting up category..");
	$insert['post_category']	= $post->create_category();
	echo " OK<br/>\n";
}
flush();
sleep(5);
self::e('Saving post..');
$post_id	= wp_insert_post($insert, true);

if(is_wp_error($post_id)){
	echo ' FAILED<br/><div class="notify notify-error">' . $post_id->get_error_message() . '</div>';
	return;
}
flush();

$upload_dir	= wp_upload_dir();
self::$var['upload_dir']	= $upload_dir;
self::$var['upload_path']	= wp_mkdir_p( $upload_dir['path'] ) ? $upload_dir['path'] . '/' :  $upload_dir['basedir'] . '/' ;
self::$var['get_args']		= array(
							'timeout'	=>	30,
							'sslverify' => false,
							'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:37.0) Gecko/20100101 Firefox/37.0'
						);

include self::f('inc/function_post.php');

if( self::$config['autoset_thumbnail'] )
{
	self::e('Seting up Post Featured Image / Thumbnail..');
	if( playstore_api_set_post_thumbnail($post_id, $post->data) ){
		echo ' OK<br/>';
	}else echo ' <br/>' . self::_('FAILED TO DOWNLOAD APP ICON') . '<br/>';
	flush();

}

if( self::$config['download_screenshot'] )
{
	echo self::_('Saving screenshot images..') . ' :<br/>';
	if( playstore_api_download_images($post_id, $insert['post_content'], $post->data) ){
		wp_update_post( array(
							'ID' => $post_id,
							'post_content' => $insert['post_content']
						));
		echo 'OK<br/>';
	}else echo '<br/>' . self::e('FAILED TO DOWNLOAD SCREENSHOT IMAGES') . '<br/>';

}
$redirect	= $post_status == 'publish' ? get_permalink( $post_id ) : "post.php?post=$post_id&action=edit";
flush();

// Elementor Support
if(class_exists('Elementor\Utils')){    
	include Playstore_API::f('inc/elementor_template.php');
}

?>
<div class="notice notice-success"><h2><?php self::e('DONE') ?></h2></div>
<script type="text/javascript">
		document.location = "<?php echo $redirect ?>";
</script>
