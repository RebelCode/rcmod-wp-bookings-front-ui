<?php

namespace RebelCode\Bookings\WordPress\Module;

use Psr\Container\ContainerInterface;
use RebelCode\Modular\Module\AbstractBaseModule;

class WpBookingsFrontUi extends AbstractBaseModule
{
    static $bookingWidgetId = 0;

    /**
     * WpBookingsFrontUi constructor.
     *
     * @since [*next-version*]
     *
     * @param $key
     * @param $containerFactory
     *
     * @throws \Dhii\Exception\InternalException
     */
    public function __construct($key, $containerFactory)
    {
        $this->_initModule(
            $containerFactory,
            $key,
            [],
            $this->_loadPhpConfigFile(RC_WP_BOOKINGS_FRONT_UI_MODULE_CONFIG)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function setup()
    {
        return $this->_createContainer();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null) {}

    /**
     * Render booking holder and enqueue styles and scripts.
     *
     * @since [*next-version*]
     *
     * @param $params
     * @return string
     */
    public function render($params = [])
    {
        $bookingHolder = sprintf($this->_getConfig()['holder_template'], static::$bookingWidgetId, json_encode($params));

        static::$bookingWidgetId++;

        $this->_enqueueAssets($params);

        return $bookingHolder;
    }

    /**
     * Add WP styles and scripts enqueuing.
     *
     * @since [*next-version*]
     *
     * @param array $params
     */
    protected function _enqueueAssets($params = [])
    {
        wp_enqueue_script(RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY, $this->_getConfig()['script'], [], false, true);
        wp_enqueue_style(RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY, $this->_getConfig()['style']);
    }
}