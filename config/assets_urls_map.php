<?php

return [
    'require' => 'https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.js',

    'wizard_ui/dist/app.min.js' => 'https://unpkg.com/@rebelcode/bookings-wizard@0.1.0/dist/app.min',
    'wizard_ui/assets/main.js' => plugins_url(WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR.'/assets/main.js', EDDBK_FILE),

    'wizard_ui/dist/eddbk-wizard-ui.css' => plugins_url(WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR.'/dist/eddbk-wizard-ui.css', EDDBK_FILE),
];
