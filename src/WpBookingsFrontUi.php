<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Event\EventFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\EventManager\EventManagerInterface;
use RebelCode\Modular\Module\AbstractBaseModule;
use Dhii\Util\String\StringableInterface as Stringable;

class WpBookingsFrontUi extends AbstractBaseModule
{
    static $bookingWidgetId = 0;

    protected $template;

    protected $apiBaseUrl;

    /**
     * @since [*next-version*]
     *
     * @var int Cart page ID
     */
    protected $cartPageId;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $key The module key.
     * @param string[]|Stringable[] $dependencies The module  dependencies.
     * @param ContainerFactoryInterface $configFactory The config factory.
     * @param ContainerFactoryInterface $containerFactory The container factory.
     * @param ContainerFactoryInterface $compContainerFactory The composite container factory.
     * @param EventManagerInterface $eventManager The event manager.
     * @param EventFactoryInterface $eventFactory The event factory.
     */
    public function __construct(
        $key,
        $dependencies,
        $configFactory,
        $containerFactory,
        $compContainerFactory,
        $eventManager,
        $eventFactory
    )
    {
        $this->_initModule($key, $dependencies, $configFactory, $containerFactory, $compContainerFactory);
        $this->_initModuleEvents($eventManager, $eventFactory);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function setup()
    {
        /*
         * @todo: remove it when composite container is ready
         */
        return $this->_setupContainer(
            $this->_loadPhpConfigFile(RC_WP_BOOKINGS_FRONT_UI_MODULE_CONFIG),
            [
                'wp_bookings_front_ui' => function () {
                    return $this;
                },
                'bookings_front_ui/options_container' => function () {
                    return new OptionsContainer();
                },
            ]);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null)
    {
        $this->template = $c->get('bookings_front_ui/holder_template');
        $this->apiBaseUrl = '/' . $c->get('eddbk_rest_api/namespace');
        $this->cartPageId = $c->get('bookings_front_ui/options_container')->get('edd_settings/purchase_page');
    }

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
        $params['apiBaseUrl'] = $this->_getApiBaseUrl();
        $params['redirectUrl'] = $this->_getRedirectUrl();

        $bookingHolder = sprintf(
            $this->template, 
            static::$bookingWidgetId, 
            json_encode($params)
        );

        static::$bookingWidgetId++;

        return $bookingHolder;
    }

    /**
     * Get cart URL on which customer will be redirected after successfull booking creation.
     * 
     * @since [*next-version*]
     * 
     * @return string Cart URL to redirect user on.
     */
    protected function _getRedirectUrl()
    {
        return get_permalink($this->cartPageId);
    }

    /**
     * Get API base url.
     * 
     * @since [*next-version*]
     * 
     * @return string
     */
    protected function _getApiBaseUrl()
    {
        return rest_url($this->apiBaseUrl);
    }

    /**
     * Add WP styles and scripts enqueuing.
     *
     * @since [*next-version*]
     */
    public function enqueueAssets($c)
    {
        wp_enqueue_script(RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY . '-main', $c->get('bookings_front_ui/main_script'), [], false, true);
        wp_enqueue_script(RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY, $c->get('bookings_front_ui/script'), [], false, true);
        wp_enqueue_style(RC_WP_BOOKINGS_FRONT_UI_MODULE_KEY, $c->get('bookings_front_ui/style'));
    }
}