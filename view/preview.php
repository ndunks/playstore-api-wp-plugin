<?php if(!defined('PLAYSTORE_API_URL')) die();

if(empty(@$_REQUEST['id']))
	return;

try {
	$post	= self::get_app($_REQUEST['id']);
} catch (Exception $e) {
	echo '<div class="alert alert-danger">ERROR:<br/>' . $e->getMessage() . '</div>';
	return;
}
?>
<div class="well dark text-center">
	<h1><?php echo self::_('Preview') . ' ' . ucwords($post->name) . ' ' . $post->version ?></h1>
	<div class="btn-group" role="group">
		<a href="<?php echo self::u() ?>" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
		<a href="javascript:history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
		<a href="<?php echo self::v('post&publish=1&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-paper-plane"></i> <?php self::e('Publish Post') ?></a>
		<a href="<?php echo self::v('post&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php self::e('Make Post') ?></a>
		<a href="<?php echo self::v('detail&id=' . $post->package) ?>" class="btn btn-primary"><i class="fa fa-list-alt"></i> <?php self::e('View Detailed Data') ?></a>
	</div>
</div>
<table class="table">
	<tr><td><?php self::e('Post Title') ?></td><td><?php echo $post->get_title() ?></td></tr>
	<tr><td><?php self::e('Post Name/Slug') ?></td><td><code><?php echo $post->get_slug() ?></code></td></tr>
	<tr><td><?php self::e('Post Meta') ?></td><td><pre><?php echo json_encode($post->get_meta(true), JSON_PRETTY_PRINT) ?></pre></td></tr>
	<tr><td><?php self::e('Template Used') ?></td><td><code><?php echo basename($this->get_user_template()) ?></code></td></tr>
</table>
<h2 class="text-center"><?php self::e('Post Content Preview') ?></h2>
<hr/>
<?php $post->get_content(true) ?>