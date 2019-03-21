<?php
use Elementor\Utils;

// grab global var from /view/post.php

$elementor = [
    'version' => '2.5.9',
    'edit_mode' => 'builder',
    'template_type' => 'post',
    'generator' => 'playstore-api',
    'data' => []
];


// Carousel Block
if(isset(Playstore_API::$var['downloaded_images'])){

    $elementor['data'][] = 
        [
            'id' => Utils::generate_random_string(),
            'elType' => 'section',
            'isInner'   => false,
            'elements' => [
                [
                    'id' => Utils::generate_random_string(),
                    'elType' => 'column',
                    'isInner'   => false,
                    'elements' => [ 
                        [
                            'id' => Utils::generate_random_string(),
                            'elType' => 'widget',
                            'widgetType' => 'image-carousel',
                            'settings' => [
                                'carousel' => Playstore_API::$var['downloaded_images']
                            ],
                        ]
                     ]
                ],
            ],
        ];
}

// Desccription block and block info
$left_block = [];

// Description Heading
$left_block[] =
    [
        "id" =>  Utils::generate_random_string(),
        "elType" => "widget",
        "widgetType" => "heading",
        "settings" => [
            "title" => __("Description")
        ],
        "elements" => []
    ];

// Description Content
$left_block[] =
    [
        'id' => Utils::generate_random_string(),
        'elType' => 'widget',
        'widgetType' => 'text-editor',
        'settings' =>
        [
            'editor' => $post->data['description'],
        ]
    ];

if( strlen(trim($post->data['recent_changes'])) > 0 ){
    // Recent changes
    // What's New Heading
    $left_block[] =
        [
            "id" =>  Utils::generate_random_string(),
            "elType" => "widget",
            "settings" =>
            [
                "title" => __("What's New")
            ],
            "elements" => [],
            "widgetType" => "heading"
        ];
        // What's New Content
    $left_block[] =
        [
            'id' => Utils::generate_random_string(),
            'elType' => 'widget',
            'widgetType' => 'text-editor',
            'settings' =>
            [
                'editor' => $post->data['recent_changes'],
            ]
        ];
}

// Right block
ob_start();

include Playstore_API::f('inc/elementor_appinfo.php');
$appinfo_content = ob_get_clean();


// Append left and right block
$elementor['data'][] =
    [
        'id' => Utils::generate_random_string(),
        'elType' => 'section',
        'isInner'   => false,
        "settings" =>
        [
            "structure" => "20"
        ],
        'elements' =>
        [
            [
                'id' => Utils::generate_random_string(),
                'elType' => 'column',
                'isInner'   => false,
                "settings" =>
                [
                    "_column_size" => 50,
                    "_inline_size" => null
                ],
                'elements' => $left_block
            ],
            [
                'id' => Utils::generate_random_string(),
                'elType' => 'column',
                'isInner'   => false,
                "settings" =>
                [
                    "_column_size" => 50,
                    "_inline_size" => null
                ],
                'elements' =>
                [
                    [
                        'id' => Utils::generate_random_string(),
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' =>
                        [
                            'editor' => $appinfo_content,
                        ]
                    ]
                ]
            ]
        ]
    ];
//goto skiped;

// Download Block
$elementor['data'][] =
    [
        'id' => Utils::generate_random_string(),
        'elType' => 'section',
        'isInner'   => false,
        'settings' =>
        [
            "height"    => "min-height",
            "custom_height" => 
            [
                "unit"  => "px",
                "size"  => 100,
                "sizes" => []
            ],
            "align" => "center"
        ],
        'elements' =>
        [
            [
                'id' => Utils::generate_random_string(),
                'elType' => 'column',
                'isInner'   => false,
                'elements' =>
                [
                    [
                        "id" =>  Utils::generate_random_string(),
                        "elType" => "widget",
                        "settings" =>
                        [
                            "title" => sprintf("%s %s %s %s!", __("Download"), esc_html($post->data['name']), $post->data['version'], __('Now') ),
                            //"title" => "Download [apk name] [apk version] Now!",
                            "align" => "center"
                        ],
                        "elements" => [],
                        "widgetType" => "heading"
                    ]
                ]
            ]
        ],
    ];
$elementor['data'][] =
    [
        'id' => Utils::generate_random_string(),
        'elType' => 'section',
        'isInner'   => false,
        "settings" =>
        [
            "structure" => 20
        ],
        'elements' =>
        [
            [
                'id' => Utils::generate_random_string(),
                'elType' => 'column',
                'isInner'   => false,
                "settings" =>
                [
                    "_column_size" => 50,
                    "_inline_size" => null
                ],
                'elements' =>
                [
                    [
                        "id"    => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "widgetType"    => "heading",
                        "settings"  =>
                        [
                            "title" => __('Direct Download Link'),
                            "header_size"   => "h3",
                            "align" => "center",
                            "elements"  => [],
                        ],
                    ],
                    [
                        "id"    => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "widgetType"    => "image",
                        "settings"  =>
                        [
                            "image" => isset(Playstore_API::$var['set_post_thumbnail']) ? 
                                Playstore_API::$var['set_post_thumbnail'] : 
                                [ "url"   => $post->data['icon'] . '=w48' ],
                            "image_size"    => "thumbnail",
                            "link_to"   => "custom",
                            "link"  =>
                            [
                                "url"   => "[playstore_api_get_download_url]",
                                "is_external"   => "",
                                "nofollow"  => ""
                            ]
                        ],
                        "elements"  => [],
                    ],
                    [
                        "id"    => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "widgetType"    => "text-editor",
                        "settings"  =>
                        [
                            "editor"    => sprintf( '<b>%s</b><br/><a href="%s">%s %s</a>',
                                                esc_html( $post->data['name'] . ' ' . $post->data['version'] ),
                                                '[playstore_api_get_download_url]', 
                                                __('Download'),
                                                $post->data['file_size']
                                            ),
                            "align" => "center"
                        ],
                        "elements"  => [],
                    ]
                ]
            ],
            [
                'id' => Utils::generate_random_string(),
                'elType' => 'column',
                'isInner'   => false,
                "settings" =>
                [
                    "_column_size" => 50,
                    "_inline_size" => null
                ],
                'elements' =>
                [
                    [
                        "widgetType"=> "heading",
                        "id"        => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "settings"  =>
                        [
                            "title" => __("Visit on Playstore"),
                            "header_size"   => "h3",
                            "align" => "center"
                        ],
                        "elements"  => [ ],
                    ],
                    [
                        "id"    => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "widgetType"    => "image",
                        "settings"  =>
                        [
                            "image" =>
                            [
                                "url"   => PLAYSTORE_API_URL . '/res/img/google-play-badge.png',
                            ],
                            "image_size"    => "full",
                            "align" => "center",
                            "link_to"   => "custom",
                            "link"  =>
                            [
                                "url"   => $post->data['url'],
                                "is_external"   => "",
                                "nofollow"  => ""
                            ],
                            "_element_id" => "google-play-badge"
                        ],
                        "elements"  => [],
                    ]
                ]
            ]
        ]
    ];

    
$elementor['data'] = wp_slash( wp_json_encode($elementor['data']) );


// Save elementor meta
foreach($elementor as $key => $value){
    update_post_meta( $post_id, "_elementor_$key", $value);
}


// Change redirect to elementor editor
if($post_status != 'publish'){
    $redirect = get_admin_url( null, "post.php?post=$post_id&action=elementor");
}
