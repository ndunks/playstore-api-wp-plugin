<div>
	<div id="playstore-api-bscarousel" class="carousel slide" data-ride="carousel">
		<!-- Bootstrap Carousel -->
		<?php
			$indicators = [];
			$images		= [];
			foreach ($data['screenshot'] as $i => $src){
				$class = $i == 0 ? 'active' : '';
				$indicators[] 	= sprintf('<li data-target="#playstore-api-bscarousel" data-slide-to="%d" class="%s"></li>', $i, $class);
				$images[]		= sprintf('<div class="carousel-item %s"><img src="%s" alt="%s"></div>',
											$class, $src, esc_attr( $data['name'] . ' Screendshot ' . ($i+1) ) );
			}

		?>
		 <ol class="carousel-indicators"><?php echo implode("\n", $indicators) ?></ol>
		<div class="carousel-inner"><?php echo implode("\n", $images) ?></div>
		<a class="carousel-control-prev" href="#playstore-api-bscarousel" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only"><?php _e('Previous') ?></span>
		</a>
		<a class="carousel-control-next" href="#playstore-api-bscarousel" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only"><?php _e('Next') ?></span>
		</a>
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
				<div>
				<?= $data['recent_changes'] ?>
				</div>
				<?php endif ?>
			</div>
			<div class="col-sm-5">
				<div class="panel panel-default">
					<table class="table table-sm table-bordered table-details">
						<tr><td>Developer</td>
							<td>
								<a href="https://play.google.com/store/apps/developer?id=<?= urlencode($data['developer_name']) ?>" rel="nofollow" target="_blank">
								<span><?= $data['developer_name'] ?></span>
								</a>
							</td>
						</tr>
						<?php

							if(isset($data['upload_date']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Published', $data['upload_date'] );

							if(isset($data['downloads']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Downloaded', $data['downloads']);

							if(isset($data['operatingSystems']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Android Version', $operatingSystems);
							
							if(isset($data['type']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Type', $data['type'] );
							
							if(isset($data['category']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Category', $data['category'] );
							
							if(isset($data['version']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Version', $data['version'] );
							
							if(isset($data['file_size']))
								printf('<tr><td>%s</td><td>%s</td></tr>', 'Size', $data['file_size'] );
							
							if(isset($data['rating'])): ?>
						<tr>
							<td colspan="2">
								<div class="text-center">
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