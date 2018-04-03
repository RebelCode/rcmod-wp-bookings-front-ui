<?php


define('RC_WP_BOOKINGS_FRONT_UI_MODULE_DEP_DIST', RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/node_modules/bookings-client/dist/static');

return [
    'holder_template' => '<div data-eddbk-widget-id="%s" data-config=\'%s\'></div>',
    'style' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_DEP_DIST . '/css/app.css', EDDBK_FILE),
    'script' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_DEP_DIST . '/js/app.js', EDDBK_FILE),
    'main_script' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/dist/main.js', EDDBK_FILE),
];