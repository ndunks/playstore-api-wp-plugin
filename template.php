<div itemscope="" itemtype="http://schema.org/MobileApplication">
	<meta itemprop="name" content="<?= htmlentities($data['name']) ?>"/>
	
	<div id="app-images">
		<?php 
		foreach ($data['screenshot'] as $i => $src){
			printf('<img class="thumbnail" itemprop="screenshot" src="%s" title="%s">', $src, $data['name'] . ' Screendshot ' . ($i+1) );
		}
		 ?>
	</div>
	<?php if(!empty($data['youtube_id'])):
	 ?>
	 <div class="text-center">
		<iframe style="display:block; margin: 10px auto" width="560" height="315" src="https://www.youtube.com/embed/<?php echo $data['youtube_id'] ?>" frameborder="0" class="app-video" allowfullscreen="allowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>
	</div>
	<?php endif; ?>
	<div class="well" id="app_info">
		<div class="row">
			<div id="description" class="col-sm-7">
				<h2>Description</h2>
				<?php echo $data['description'] ?>
				<?php if($data['recent_changes']) : ?>
				<h2>What's New</h2>
				<div itemprop="releaseNotes">
				<?= $data['recent_changes'] ?>
				</div>
				<?php endif ?>
			</div>
			<div class="col-sm-5">
				<div class="panel panel-default">
					<table class="table table-sm table-bordered table-details">
						<tr><td>Developer</td>
							<td itemprop="author" itemscope="" itemtype="http://schema.org/Organization">
								<a href="https://play.google.com/store/apps/developer?id=<?= urlencode($data['developer_name']) ?>" itemprop="url" rel="nofollow" target="_blank">
								<span itemprop="name"><?= $data['developer_name'] ?></span>
								</a>
							</td>
						</tr>
						<?php

							if(isset($data['upload_date']))
								printf('<tr><td>%s</td><td itemprop="datePublished">%s</td></tr>', 'Published', $data['upload_date'] );

							if(isset($data['downloads']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Downloaded', $data['downloads']);

							if(isset($data['operatingSystems']))
								printf('<tr><td>%s</td><td itemprop="operatingSystem">%s</td></tr>', 'Android Version', $operatingSystems);
							
							if(isset($data['type']))
								printf('<tr><td>%s</td><td itemprop="applicationCategory">%s</td></tr>', 'Type', $data['type'] );
							
							if(isset($data['category']))
								printf('<tr><td>%s</td><td itemprop="applicationSubCategory">%s</td></tr>', 'Category', $data['category'] );
							
							if(isset($data['version']))
								printf('<tr><td>%s</td><td itemprop="softwareVersion">%s</td></tr>', 'Version', $data['version'] );
							
							if(isset($data['file_size']))
								printf('<tr><td>%s</td><td itemprop="fileSize">%s</td></tr>', 'Size', $data['file_size'] );
							
							if(isset($data['rating'])): ?>
						<tr>
							<td colspan="2">
								<div class="text-center" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
									<meta content="<?php echo $data['rating']['star_rating'] ?>" itemprop="ratingValue"/>
									<meta content="<?php echo $data['rating']['ratings_count'] ?>" itemprop="ratingCount"/>
									<div class="h3">Rating <?php echo number_format($data['rating']['star_rating'],2) ?></div>
									<div class="star-container">
										<div class="star">
											<div class="star-fill" style="width: <?php echo $data['rating_percent'] ?>%;"></div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<?php endif; ?>
					</table>
				</div>
			</div>
		</div>
	</div>

	<h2 class="text-center">Download <?= $data['name'] . ' ' . $data['version'] ?> Now!</h2>
	<div class="well text-center" id="download">
		<div class="row">
			<div class="col-sm-6">
				<h3>Direct Download Link</h3>
					<a href="[playstore_api_get_download_url]">
						<img src="<?php echo $data['icon'] ?>=w48" />
					</a>
					<br/>
					<b><?php echo $data['name'] . ' ' . $data['version'] ?></b><br/>
					<b><a href="[playstore_api_get_download_url]">Download <?php echo $data['file_size'] ?></a></b>
			</div>
			<div class="col-sm-6">
				<h3>Visit on Playstore</h3>
				<a href="<?php echo $data['url']?>" target="_blank">
					<img src="<?php echo PLAYSTORE_API_URL . '/res/img/google-play-badge.png' ?>" />
				</a>
			</div>
		</div>
	</div>
</div>