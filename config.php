<?php

return [
    'holder_template' => '<div id="booking-widget-%s" data-config="%s"></div>',
    'script' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/dist/rc-booking-widget.min.js', EDDBK_FILE),
    'style' => plugins_url(RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR . '/dist/rc-booking-widget.min.css', EDDBK_FILE),
];