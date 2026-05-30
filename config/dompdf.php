<?php

return [
    'show_warnings'   => false,
    'orientation'     => 'landscape',
    'defines'         => [
        'font_dir'              => storage_path('fonts/'),
        'font_cache'            => storage_path('fonts/'),
        'temp_dir'              => sys_get_temp_dir(),
        'chroot'                => realpath(base_path()),
        'allowed_protocols'     => ['file://', 'http://', 'https://'],
        'enable_font_subsetting'=> false,
        'pdf_backend'           => 'CPDF',
        'default_media_type'    => 'screen',
        'default_paper_size'    => 'a4',
        'default_paper_orientation' => 'landscape',
        'default_font'          => 'Arial',
        'dpi'                   => 96,
        'enable_php'            => false,
        'enable_javascript'     => false,
        'enable_remote'         => true,
        'font_height_ratio'     => 1.1,
        'enable_css_float'      => false,
        'enable_html5_parser'   => true,
    ],
];
