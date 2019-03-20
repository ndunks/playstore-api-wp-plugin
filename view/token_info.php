<?php if(!defined('PLAYSTORE_API_URL')) die(); 
require_once self::f('inc/function.php');
?>
<div>
	<a href="<?php echo self::u() ?>"><?php echo self::$title ?></a> &raquo; <?php self::e('Token Info'); ?>
</div>
<h1>Token Info <span code><?php echo self::$config['api_token'] ?></span></h1>
<table class="table">
<?php
	$data	= self::query('info');
	foreach ($data as $key => $value)
	{
		if(is_bool($value))
			$value = $value ? 'true' : 'false';
		printf('<tr><th>%s</th><td>:</td><td>%s</td></tr>', ucwords(strtr($key, '_-', '  ')), esc_html($value));
	}
?>
</table>