<?php if(!defined('PLAYSTORE_API_URL')) die();

if(isset($_POST['save']))
{
	if(self::$DEMO)
	{
		?><div class="notice notice-error"> 
			<p><strong><?php self::e('Cannot save template.') ?></strong></p>
		</div><?php
	}else
	{
		$html	= stripslashes($_POST['playstore_api_template']);
		if( $this->set_user_template($html)): ?>
			<div class="notice notice-success"> 
				<p><strong><?php self::e('Template saved.') ?></strong></p>
			</div>
		<?php else: ?>
			<div class="notice notice-error"> 
				<p><strong><?php self::e('Failed to save template.') ?></strong></p>
			</div>
		<?php endif;
	}
}elseif(isset($_POST['reset']))
{
	$this->delete_user_template();
	?><div class="notice notice-success"> 
		<p><strong><?php self::e('Default template loaded.') ?></strong></p>
	</div><?php
}

$template	= file_get_contents($this->get_user_template());

?>
<div>
	<a href="<?php echo self::u() ?>"><?php echo self::$title ?></a> &raquo; Edit Template
</div>
<form method="post">
<h1>Edit Post Template</h1>
<p>Write using HTML and PHP Code. You can customize your template, it different for each user.</p>
<textarea name="playstore_api_template" class="large-text" style="height:500px"><?= htmlentities($template) ?></textarea>
<hr>
<p class="submit">
	<input type="submit" name="save" class="button button-primary" value="Save">
	<input onclick="return confirm('Are you sure?')" type="submit" name="reset" class="button button-secondary" value="Reset Default"/>
</p>
</form>