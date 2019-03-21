<?php if(!defined('PLAYSTORE_API_URL')) die();
try {
	$data	= Playstore_API::query( 'top_charts', array(
							'next' => isset($_REQUEST['next']) ? $_REQUEST['next'] : '',
							'rank_type'	=> 'NEW_FREE',
							'count'	=> 30
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
if(!empty(@$data['title']))
	echo '<h1 class="text-center">' . $data['title'] . '</h1>';

echo '<table class="table table-bordered">';
foreach ($apps as $app)
{
	extract($app);
	printf('
		<tr>
			<td width="64px"><img class="icon" src="%s=w48"/></td>
			<td><a href="%s" class="h5">%s</a></td>
			<td>%s</td>
			<td>
				<a href="%s" class="btn btn-sm btn-default" title="%s" target="_blank"><i class="fa fa-paper-plane"></i> %s</a> 
				<a href="%s" class="btn btn-sm btn-default" title="%s" target="_blank"><i class="fa fa-pencil"></i> %s</a> 
				<a href="%s" class="btn btn-sm btn-default" title="%s" target="_blank"><i class="fa fa-eye"></i> %s</a>
			</td>
		</tr>',
			$icon, self::v('detail&id=' . $package), $name, $creator, 
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
