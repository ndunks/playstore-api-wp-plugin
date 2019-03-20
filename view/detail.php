<?php if(!defined('PLAYSTORE_API_URL')) die();
if(empty(@$_REQUEST['id'])) return;

try {
	$post	= self::get_app($_REQUEST['id']);
} catch (Exception $e) {
	echo '<div class="alert alert-danger">ERROR:<br/>' . $e->getMessage() . '</div>';
	return;
}
?>
<div class="well dark text-center">
	<h1><?php echo self::_('APP Detail') . ' ' . ucwords($post->name) . ' ' . $post->version ?></h1>
	<div class="btn-group" role="group">
		<a href="<?php echo self::u() ?>" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
		<a href="javascript:history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
		<a href="<?php echo self::v('post&publish=1&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-paper-plane"></i> <?php self::e('Publish Post') ?></a>
		<a href="<?php echo self::v('post&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php self::e('Make Post') ?></a>
		<a href="<?php echo self::v('preview&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-eye"></i> <?php self::e('Preview') ?></a>
	</div>
</div>
<hr/>
<table class="table table-bordered">
	<tr><th>Variable</th><th>Value</th></tr>
	<?php
	foreach ($post->data as $key => &$value)
	{
		echo '<tr><th>' . esc_html($key) . '</th><td>';
		if(is_null($value)){
			echo '<i class="text-muted">NULL</i>';
		}elseif(is_array($value)){
			echo '<b>ARRAY :</b><br/>';
			foreach ($value as $k => &$v)
			{
				echo '<b>[' . esc_html($k) . '] :</b> ' . esc_html($v) . '<br/>';
			}
		}else{
			echo esc_html($value);
		}
		echo '</td></tr>';
	}
	?>
</table>