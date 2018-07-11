<?php

return [
    'require' => 'https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.js',

    'wizard_ui/dist/app.min.js' => 'https://unpkg.com/@rebelcode/booking-wizard@0.1.0/dist/js/app.min',
    'wizard_ui/assets/main.js'  => plugins_url(RC_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/assets/main.js', EDDBK_FILE),

    'wizard_ui/dist/wp-bookings-front-ui.css' => plugins_url(RC_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/dist/wp-bookings-front-ui.css', EDDBK_FILE),
];
