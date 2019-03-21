<?php if(!defined('PLAYSTORE_API_URL')) die(); ?>
<div class="text-center">
	<h1><i class="fa fa-android"></i> <?= self::$title . ' ' . self::$version ?></h1>
	<div class="btn btn-group">
		<?php if(is_admin() && class_exists('Playstore_API_Admin')): ?>
		<a href="options-general.php?page=<?php echo Playstore_API_Admin::$name ?>" class="btn btn-outline-primary btn-sm">
			<span class="dashicons dashicons-admin-generic"></span>Settings
		</a>
		<?php endif; ?>
		<a href="<?php echo self::v('edit_template') ?>" class="btn btn-outline-primary btn-sm">
			<span class="dashicons dashicons-admin-appearance"></span>Post Template
		</a>
		<a href="<?php echo self::v('cache') ?>" class="btn btn-outline-primary btn-sm">
			<span class="dashicons dashicons-backup"></span>Cache Status
		</a>
	</div>
</div>
<form class="mx-auto col-sm-8 col-md-6 my-4" action="admin.php">
	<input type="hidden" name="page" value="<?= self::$name ?>">
	<div class="input-group">
		<input type="text" class="form-control" name="q" value="<?= isset($_GET['q']) ? esc_html($_GET['q']) : '' ?>" placeholder="Search Package name or APP Name">
		<span class="input-group-append">
			<button class="btn btn-default" type="submit"><span class="dashicons dashicons-search"></span></button>
		</span>
	</div>
</form>
<?php include self::f( empty(@$_REQUEST['q']) ? 'view/home.php' : 'view/result.php' ); ?>
