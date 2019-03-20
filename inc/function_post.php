<?php if(!defined('PLAYSTORE_API_URL')) die(); 

require_once(ABSPATH . 'wp-admin/includes/image.php');

function playstore_api_save_attachment($post_id, $url, $title, $desc)
{
	$postname	= sanitize_title($title);
	$query		= new Wp_Query(array(
							'post_per_page' => 1,
							'post_type'     => 'attachment',
							'name'          => $postname,
						));

	if ( !empty($query->posts) ){
		$post		= $query->posts[0];
		$attach_id	= $post->ID;
		$file_link	= wp_get_attachment_url($attach_id);
		echo ' [Exists] ';
		return array('id' => $attach_id, 'url' => $file_link);
	}

	$response = wp_remote_get( $url, Playstore_API::$var['get_args'] );
	
	if(is_wp_error($response)){
		echo $response->get_error_message();
		return false;
	}

	if( empty($response['body']) ){
		echo "[Empty Response Body]";
		return false;
	}

	$img_type	= strtolower($response['content-type']);
	$extension	= 'png';

	if(strpos($img_type, 'jpg')		!== false || strpos($img_type, 'jpeg') !== false)
		$extension	= 'jpg';
	elseif(strpos($img_type, 'gif') !== false)
		$extension	= 'gif';
	elseif(strpos($img_type, 'bmp') !== false || strpos($img_type, 'bitmap') !== false)
		$extension	= 'bmp';
	$filename   = "$postname.$extension";
	$file		= Playstore_API::$var['upload_path'] . $filename;
	if(file_exists($file))
	{
		echo " [Exists $file] ";
		die('<h1>Please Code again for get attachment by filename!</h1>');
		
	}elseif( ! file_put_contents( $file, $response['body'] )){
		echo " [Fail creating file $file] ";
		return false;
	}

	// Check image file type
	$filetype		= wp_check_filetype( $filename);
	$attachment 	= array(
						'post_mime_type'=> $filetype['type'],
						'post_title'	=> $title,
						'post_content'	=> $desc,
						'post_status'	=> 'inherit'
					);

	// Create the attachment
	$attach_id		= wp_insert_attachment( $attachment, $file, $post_id );
	$file_link		= Playstore_API::$var['upload_dir']['url'] . '/' . $filename;
	$attach_data	= wp_generate_attachment_metadata( $attach_id, $file );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	return array('id' => $attach_id, 'url' => $file_link);
}

function playstore_api_download_images($post_id, &$html, &$data)
{
	// Include image.php
	if(empty($data['screenshot'])){
		echo ' [Empty Screenshot] ';
		return false;
	}
	
	$downloaded		= 0;
	foreach ($data['screenshot'] as $key => $img_url)
	{
		echo "GET $img_url ..";

		$attach	= playstore_api_save_attachment(
													$post_id,
													$img_url,
													$data['name'] . ' ' . $data['version'] . ' Screenshot ' . ($key+1),
													"Android application screenshot of " . $data['name']
												);
		if( !$attach ){
			echo ' [Fail creating attachment] ';
		}else{
			$html	= str_replace($img_url, $attach['url'], $html);
			echo '. OK';
			$downloaded++;
		}

		echo "\n<br/>";
	}
	return $downloaded;
}

function playstore_api_set_post_thumbnail($post_id, &$data)
{

	if(empty(@$data['icon'])){
		echo '[Empty Icon]';
		return false;
	}

	$attach	= playstore_api_save_attachment(
												$post_id,
												$data['icon'],
												$data['name'] . ' ' . $data['version'] . ' Icon',
												"Android application icon of " . $data['name']
											);
	if( !$attach )
		return false;

	set_post_thumbnail( $post_id, $attach['id'] );
	return true;
}
?>
