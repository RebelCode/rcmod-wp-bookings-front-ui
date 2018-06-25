<?php
use Psr\Container\ContainerInterface;
use \RebelCode\Bookings\WordPress\Module\WpBookingsFrontUi;

define('WP_BOOKINGS_FRONT_UI_MODULE_RELATIVE_DIR', 'modules/rcmod-wp-bookings-front-ui');
define('WP_BOOKINGS_FRONT_UI_MODULE_DIR', __DIR__);
define('WP_BOOKINGS_FRONT_UI_MODULE_CONFIG', WP_BOOKINGS_FRONT_UI_MODULE_DIR . '/config.php');
define('WP_BOOKINGS_FRONT_UI_MODULE_KEY', 'wp_bookings_front_ui');

return function(ContainerInterface $c) {
    return new WpBookingsFrontUi(
        WP_BOOKINGS_FRONT_UI_MODULE_KEY,
        [],
        $c->get('config_factory'),
        $c->get('container_factory'),
        $c->get('composite_container_factory'),
        $c->get('event_manager'),
        $c->get('event_factory')
    );
};