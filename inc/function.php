<?php if(!defined('PLAYSTORE_API_URL')) die(); 
function playstore_api_format_size($size)
{
	//$size	= filesize($apk_process);
	$lb		= 'Byte';
	if($size > 1024)
	{
		$size	= $size/1024;
		$lb		= 'KB';
	}
	if($size > 1024)
	{
		$size	= $size/1024;
		$lb		= 'MB';
	}
	if($size > 1024)
	{
		$size	= $size/1024;
		$lb		= 'GB';
	}
	$size	= number_format($size, 2);
	return "$size $lb";
}