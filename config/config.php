<?php

return [
    'bookings_front_ui' => [
        'holder_template' => '<div data-eddbk-wizard-holder><eddbk-wizard :config=\'%s\'></eddbk-wizard></div>',
        'edd_settings' => get_option('edd_settings'),

        /*
         * Configuration for placeholder templates.
         */
        'templates_config' => [
            'token_start'   => '${',
            'token_end'     => '}',
            'token_default' => '',
        ],

        'application_selector' => '[data-eddbk-wizard-holder]',
        'api_base_urls' => [
            'bookings' => '/eddbk/v1/bookings/',
            'services' => '/eddbk/v1/services/',
        ],
        'initial_booking_transition' => 'cart',
        'formats' => [
            'datetime' => [
                'sessionTime' => 'h:mm a',
                'dayFull' => 'dddd Do MMMM YYYY',
                'dayShort' => 'D MMMM YYYY',
                'dayKey' => 'YYYY-MM-DD',
                'appointmentStart' => 'h:mm a \o\\n dddd Do MMMM YYYY'
            ]
        ],

        'assets_urls_map_path' => WP_BOOKINGS_FRONT_UI_MODULE_CONFIG_DIR . '/assets_urls_map.php',
        'assets' => [
            'require.js' => 'require',
            'wizard' => [
                'app.min.js' => 'wizard_ui/dist/app.min.js',
                'main.js' => 'wizard_ui/assets/main.js',
            ],
            'styles' => [
                'app' => 'wizard_ui/dist/eddbk-wizard-ui.css',
            ],
        ]
    ]
];