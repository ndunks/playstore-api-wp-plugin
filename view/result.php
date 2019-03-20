<?php if(!defined('PLAYSTORE_API_URL')) die();

if(empty(@$_REQUEST['q']) && empty(@$_REQUEST['next']))
	return;
try {
	$data	= Playstore_API::query( 'search', array(
							'next' => isset($_REQUEST['next']) ? $_REQUEST['next'] : '',
							'query' => isset($_REQUEST['q']) ? $_REQUEST['q'] : '',
							'apps_count'	=> 30
							 ));
	
} catch (Exception $e) {
	echo '<div class="alert alert-danger">ERROR:<br/>' . $e->getMessage() . '</div>';
	return;
}
$apps	= array();
$title	= "Search Apps";
$next	= false;

if(empty($data) || empty($data['apps']))
{
	echo '<p class="txt-muted">Not find anything :-(</p>';
		return;
}else{
	$apps		= $data['apps'];
	$next		= $data['next'];
}

echo '<table class="table table-bordered">';
foreach ($apps as $app)
{
	extract($app);
	printf('
		<tr>
			<td width="64px"><img class="icon" src="%s=w48"/></td>
			<td><a href="%s" class="h3">%s</a></td>
			<td>%s</td>
			<td>%s</td>
			<td>
				<a href="%s" class="btn btn-small btn-default" title="%s" target="_blank"><i class="fa fa-paper-plane"></i> %s</a> 
				<a href="%s" class="btn btn-small btn-default" title="%s" target="_blank"><i class="fa fa-pencil"></i> %s</a> 
				<a href="%s" class="btn btn-small btn-default" title="%s" target="_blank"><i class="fa fa-eye"></i> %s</a>
			</td>
		</tr>',
			$icon, self::v('detail&id=' . $package), $name, $price, $creator, 
			self::v('post&publish=1&id=' . $package), self::_('Make post and publish it.'), self::_('Publish Post'),
			self::v('post&id=' . $package), self::_('Make post and edit before publish.'), self::_('Make Post'),
			self::v('preview&id=' . $package), self::_('Preview post with current template.'), self::_('Preview')
		);
}
echo '</table>';

if($data['next']): ?>
	<hr/>
	<a class="btn btn-lg btn-success" href="<?php echo self::u('q=' . esc_html($_GET['q']) . '&next=' . esc_html($data['next']) ) ?>"><?php self::e('Next Result') ?></a>
<?php endif ?>
