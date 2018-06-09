<?php

return [
    'bookings_front_ui' => [
        'holder_template' => '<div data-eddbk-widget-id="%s" data-config=\'%s\'></div>',
        'style' => 'https://unpkg.com/@rebelcode/bookings-client@0.0.8/dist/static/css/app.css',
        'script' => 'https://unpkg.com/@rebelcode/bookings-client@0.0.8/dist/static/js/app.js',
        'main_script' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/assets/main.js', EDDBK_FILE),
    ]
];