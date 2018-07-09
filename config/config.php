<?php

return [
    'bookings_front_ui' => [
        /*
         * Configuration for placeholder templates.
         */
        'templates_config' => [
            'token_start'   => '${',
            'token_end'     => '}',
            'token_default' => '',
        ],

        'state_var_name'       => 'EDDBK_WIZARD_APP_STATE',
        'application_selector' => '[data-eddbk-wizard-holder]',
        'api_endpoint_urls'    => [
            'bookings' => '/eddbk/v1/bookings/',
            'services' => '/eddbk/v1/services/',
            'sessions' => '/eddbk/v1/sessions/',
        ],
        'initial_booking_transition' => 'cart',
        'formats'                    => [
            'datetime' => [
                'sessionTime'      => 'h:mm a',
                'dayFull'          => 'dddd Do MMMM YYYY',
                'dayShort'         => 'D MMMM YYYY',
                'dayKey'           => 'YYYY-MM-DD',
                'appointmentStart' => 'h:mm a \o\\n dddd Do MMMM YYYY',
            ],
        ],
        /*
         * Data map for booking resource. Key is real key name which be sent
         * to the API. Value is field name for using on the client.
         *
         * @since [*next-version*]
         */
        'booking_data_map' => [
            'notes' => 'notes',
        ],

        'wizard_template' => [
            /*
             * List of context keys that should be rendered as JSON strings in template.
             *
             * @since [*next-version*]
             */
            'context_json_strings' => [
                'config',
            ],
        ],

        'assets_urls_map_path' => RC_BOOKINGS_FRONT_UI_MODULE_CONFIG_DIR . '/assets_urls_map.php',
        'assets'               => [
            'require.js' => 'require',
            'wizard'     => [
                'app.min.js' => 'wizard_ui/dist/app.min.js',
                'main.js'    => 'wizard_ui/assets/main.js',
            ],
            'styles' => [
                'app' => 'wizard_ui/dist/wp-bookings-front-ui.css',
            ],
        ],
    ],
];
