<?php

return [
    'bookings_front_ui' => [
        'holder_template' => '<eddbk-wizard :config=\'%s\'></eddbk-wizard>',
        'style' => 'https://unpkg.com/@rebelcode/bookings-client@0.0.10/dist/static/css/app.css',
        'script' => 'https://unpkg.com/@rebelcode/bookings-client@0.0.10/dist/static/js/app.js',
        'main_script' => plugins_url(WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/assets/main.js', EDDBK_FILE),
        'edd_settings' => get_option('edd_settings'),

        /*
         * Configuration for placeholder templates.
         */
        'templates_config' => [
            'token_start'   => '${',
            'token_end'     => '}',
            'token_default' => '',
        ],
    ]
];