<?php
use Psr\Container\ContainerInterface;
use \RebelCode\Bookings\WordPress\Module\WpBookingsFrontUi;

define('RC_WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR', 'modules/rcmod-wp-bookings-front-ui');
define('RC_WP_BOOKINGS_FRONT_UI_MODULE_DIR', __DIR__);
define('RC_WP_BOOKINGS_FRONT_UI_MODULE_CONFIG', RC_WP_BOOKINGS_FRONT_UI_MODULE_DIR . '/config.php');
define('RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY', 'bookings_front_ui');

return function(ContainerInterface $c) {
    return new WpBookingsFrontUi(
        RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY,
        $c->get('container_factory')
    );
};