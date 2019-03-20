<?php if(!defined('PLAYSTORE_API_URL')) die();
// DEFAULT CONFIG

$config	= array(
	'token'	=> array(
			'title'	=> 'Api Token',
			'desc'	=> 'Playstore API Require API Token for work. if you don\'t have any token, ' . 
						'you can get token on <a href="https://playstore-api.com/" target="_blank">playstore-api.com</a>',
			'fields'=> array(
				'api_server'=> array(
										'type'		=> 'text',
										'default'	=> 'https://data.playstore-api.com/'
									),
				'api_token'	=> array(
										'type'		=> 'text',
										'default'	=> 'DEMO_TOKEN',
										'info'		=> 'Check <a href="' . self::v('token_info') . '">token info</a>'
									)
	)),
	'post'	=> array(
			'title'	=> 'Posting Options',
			'desc'	=> 'See on <a href="' . Playstore_API::v('variable_help') .'" target="_blank" class="btn btn-sm btn-warning">Variable Helper</a> for more info.',
			'fields'=> array(
				'post_title'			=> array(
											'type'		=> 'text',
											'default'	=> 'Download {name} {version} APK Directly'
										),
				'post_slug'				=> array(
											'type'		=> 'text',
											'default'	=> 'download-{name}-{version}-by-{developer_name}'
										),
				'autoset_thumbnail'		=> array(
											'type'		=> 'checkbox',
											'default'	=> true
										),
				'download_screenshot'	=> array(
											'type'		=> 'checkbox',
											'default'	=> true
										),
				'autoset_post_category'	=> array(
											'type'		=> 'checkbox',
											'default'	=> true
										),
				'parent_category_desc'	=> array(
											'type'		=> 'text',
											'default'	=> 'Download Android {type} Directly',
											'info'		=> 'Parent category description, type may be "Application" or "Game"'
										),
				'category_desc'			=> array(
											'type'		=> 'text',
											'default'	=> 'Download Android {type} Category {category}',
											'info'		=> 'Category description, such as "Tools", "Board", "Casual", etc..'
										)
	)),
	'apk'	=> array(
			'title'	=> 'APK Download',
			'desc'	=> 'Download option and location to store downloaded APK file.',
			'fields'=> array(
				'download_apk_file'		=> array(
											'type'		=> 'select',
											'default'	=> 'yes',
											'option'	=> array(
													'yes'	=> 'Download',
													'no'	=> 'No',
													'link'	=> 'Generate Link'
												),
											'info'		=> '<b>Download</b>: Download APK file and store on this hosting.<br/>' . 
															'<b>Generate Link</b>: Generate download link to API server and send direct link to user.</br>' . 
															'<b>No</b>: Not do anything with apk file.'

										),
				'use_landing_page'		=> array(
											'type'		=> 'checkbox',
											'default'	=> true,
											'info'		=> 'Send user to specified download page before goto download file URL.'
										),
				'download_page_slug'	=> array(
											'type'		=> 'text',
											'default'	=> 'download',
											'info'		=> '<a target="_blank" href="' . self::v('create_default_page') . '">Click here</a> to re-create download page slug. You must set your permalink to "Post name"'
										),
				'download_wait'	=> array(
											'type'		=> 'text',
											'default'	=> 5,
											'info'		=> 'Download landing page timeout in seconds before direct link visible. Minimal 3 seconds.'
										),
				'apk_dir'				=> array(
											'type'		=> 'text',
											'default'	=> ABSPATH . 'apps',
											'info'		=> 'Location where APK files stored. Default is relative with your wordpress home directory.'
										),
				'apk_sub_dir'			=> array(
											'type'		=> 'text',
											'default'	=> '{year}/{month}',// or null
											'info'		=> 'Store APK file in formatted sub directory, or leave blank to store on Apk Dir.'
										),
				'apk_file_name'			=> array(
											'type'		=> 'text',
											'default'	=> '{name}-{version}-from-{domain}.apk',
											'info'		=> 'Saved APK file name.'
										)
	)),
	'wpseo'	=> array(
			'title'	=> 'WPSEO Option',
			'desc'	=> 'You can enable this option if you have installed & activated plugin WP SEO by Yoast.',
			'fields'=> array(
				'wpseo_support'			=> array(
												'type'		=> 'checkbox',
												'default'	=> false
											),
				'wpseo_focus_keyword'	=> array(
												'type'		=> 'text',
												'default'	=> 'Download {name} {version}'
											),
				'wpseo_title'			=> array(
												'type'		=> 'text',
												'default'	=> 'Download {name} {version} APK without PC'
											),
				'wpseo_description'		=> array(
												'type'		=> 'textarea',
												'class'		=> 'large-text',
												'default'	=>	'Direct Download {name} {version} APK to your PC without smartphone or playstore'
											)
	)),
	'other'	=> array(
			'title'	=> 'Other Options',
			'fields'=> array(
				'disable_shortcode'	=> array(
												'type'		=> 'checkbox',
												'default'	=> false,
												'info'		=> 'Writen shortcode (Old post) still be processed & displayed.'
											),
				'disable_shortcode_all'	=> array(
												'type'		=> 'checkbox',
												'default'	=> false,
												'info'		=> 'Full disable, no <code>[apk ..]</code> shortcode will be processed.'
											),
				'disable_css_on_editor'	=> array(
												'type'		=> 'checkbox',
												'default'	=> false,
												'info'		=> 'This plugin included CSS on post editor, you can disable it.'
											),
				'disable_bootstrap_js'	=> array(
												'type'		=> 'checkbox',
												'default'	=> false,
												'info'		=> 'You can disable if you found bootstrap conflict.'
											),
				'disable_bootstrap_css'	=> array(
												'type'		=> 'checkbox',
												'default'	=> false,
												'info'		=> 'You can disable if you found bootstrap conflict.'
											),
				'data_path'				=> array(
												'type'		=> 'text',
												'class'		=> 'large-text code',
												'default'	=> PLAYSTORE_API_DIR . 'data/',
												'info'		=> 'Plugin working direcotry, must be writable.'
											),
				'cache_dir'				=> array(
												'type'		=> 'text',
												'class'		=> 'large-text code',
												'default'	=> PLAYSTORE_API_DIR . 'data/cache/'
											),
				'cache_api'				=> array(
												'type'		=> 'text',
												'class'		=> 'large-text code',
												'default'	=> 60 * 60 * 24 * 2,
												'info'		=> 'Cache fresh (in second), or 0 for no cache'
												)
	))
);