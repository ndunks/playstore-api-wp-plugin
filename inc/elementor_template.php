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
                'elements' =>
                [ 
                    [
                        'id' => Utils::generate_random_string(),
                        'elType' => 'widget',
                        'widgetType' => 'apk-screenshot'
                    ]
                ]
            ],
        ],
    ];

// Left Block Desccription block and block info
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
        'editor' => '[apk description]',
    ]
];

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
        'editor' => '[apk recent_changes]',
    ]
];
// Right Block
$right_block = [];

// Video
$right_block[] =
[
    'id' => Utils::generate_random_string(),
    'elType' => 'widget',
    'widgetType' => 'apk-video'
];
// Detail tables
$right_block[] =
[
    'id' => Utils::generate_random_string(),
    'elType' => 'widget',
    'widgetType' => 'apk-app-info'
];
// Star Ratings
$right_block[] =
[
    'id' => Utils::generate_random_string(),
    'elType' => 'widget',
    'widgetType' => 'apk-star-rating',
    'settings' =>
    [
        "title" => "Rating [apk rating_simple]",
        "align" => "center"
    ]
];

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
                'elements' => $right_block
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
                            //"title" => sprintf("%s %s %s %s!", __("Download"), esc_html($post->data['name']), $post->data['version'], __('Now') ),
                            "title" => "Download [apk name] [apk version] Now!",
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
                    ],
                    [
                        "id"    => Utils::generate_random_string(),
                        "elType"    => "widget",
                        "widgetType"    => "text-editor",
                        "settings"  =>
                        [
                            "editor"    => '<b>[apk name] [apk version]</b><br/>' . 
                                            '<a href="[playstore_api_get_download_url]">Download [apk file_size]</a>',
                            "align" => "center"
                        ],
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
                                "url"   => '[apk url]',
                                "is_external"   => "",
                                "nofollow"  => ""
                            ],
                            "_element_id" => "google-play-badge"
                        ],
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
