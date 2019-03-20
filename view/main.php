<?php if(!defined('PLAYSTORE_API_URL')) die(); ?>
<div class="text-center">
	<h1><i class="fa fa-android"></i> <?= self::$title . ' ' . self::$version ?></h1>
	<div class="btn btn-group">
		<?php if(is_admin() && class_exists('Playstore_API_Admin')): ?>
		<a href="options-general.php?page=<?php echo Playstore_API_Admin::$name ?>" class="btn btn-warning">Settings</a>
		<?php endif; ?>
		<a href="<?php echo self::v('edit_template') ?>" class="btn btn-warning">Post Template</a>
		<a href="<?php echo self::v('cache') ?>" class="btn btn-warning">Cache Status</a>
	</div>
</div>
<form class="center-block col-sm-8 col-md-6" action="admin.php">
	<input type="hidden" name="page" value="<?= self::$name ?>">
	<div class="input-group">
		<input type="text" class="form-control" name="q" value="<?= isset($_GET['q']) ? esc_html($_GET['q']) : '' ?>" placeholder="Search Package name or APP Name">
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
		</span>
	</div>
</form>
<?php include self::f( empty(@$_REQUEST['q']) ? 'view/home.php' : 'view/result.php' ); ?>
