<?php if(!defined('PLAYSTORE_API_URL')) die(); 
require_once self::f('inc/function.php');
?>
<div>
	<a href="<?php echo self::u() ?>"><?php echo self::$title ?></a> &raquo; <?php self::e('Cache'); ?>
</div>

<div class="well text-center">
	<?php

	$size	= 0;
	$total	= 0;
	foreach (glob(self::$config['cache_dir'] . "*") as $filename)
	{
		if(is_dir($filename)) continue;
		$total++;
		$size += filesize($filename);
		if(isset($_POST['clear_cache']))
			if( ! unlink($filename) )
				echo "Fail delete $filename<br/>";
	}
	
	if(isset($_POST['clear_cache']))
		echo "<h1 class=\"text-danger\">Cache CLEARED!</h1>";
	else echo "<h1>Cached API Result</h1>Total: $total file(s), size: " . playstore_api_format_size($size);
	?>
	<form method="post" action="<?php echo self::v('storage'); ?>">
		<input type="submit" name="clear_cache" class="btn btn-warning" value="<?php self::e('Clear Cache') ?>"/>
		<hr/><?php self::e('Clearing will not affected with post that you was created'); ?>
	</form>
</div>