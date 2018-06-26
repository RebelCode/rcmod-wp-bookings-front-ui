<?php

namespace RebelCode\Bookings\WordPress\Module\Handlers;

use Dhii\Collection\MapInterface;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Invocation\InvocableInterface;
use Dhii\Util\Normalization\NormalizeArrayCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use stdClass;
use Traversable;

/**
 * Handle state output for the client application.
 *
 * @since [*next-version*]
 */
class StateEnqueueHandler implements InvocableInterface
{
    /** @since [*next-version*] */
    use NormalizeArrayCapableTrait;

    /** @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /** @since [*next-version*] */
    use StringTranslatingTrait;

    /** @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /**
     * Application container's CSS selector.
     *
     * @since [*next-version*]
     *
     * @var Stringable|string
     */
    protected $applicationSelector;

    /**
     * List of base urls for API endpoints.
     *
     * @since [*next-version*]
     *
     * @var array|MapInterface|stdClass
     */
    protected $apiBaseUrls;

    /**
     * Name of initial transition for booking.
     *
     * @since [*next-version*]
     *
     * @var Stringable|string
     */
    protected $initialBookingTransition;

    /**
     * List of datetime formats for application.
     *
     * @since [*next-version*]
     *
     * @var array|MapInterface|stdClass
     */
    protected $datetimeFormats;

    /**
     * StateEnqueueHandler constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $applicationSelector Application container's CSS selector.
     * @param MapInterface|array|stdClass $apiBaseUrls List of base urls for API endpoints.
     * @param string|Stringable $initialBookingTransition Name of initial transition for booking.
     * @param MapInterface|array|stdClass $datetimeFormats List of datetime formats for application.
     */
    public function __construct($applicationSelector, $apiBaseUrls, $initialBookingTransition, $datetimeFormats)
    {
        $this->applicationSelector = $this->_normalizeString($applicationSelector);
        $this->apiBaseUrls = $apiBaseUrls;
        $this->initialBookingTransition = $this->_normalizeString($initialBookingTransition);
        $this->datetimeFormats = $datetimeFormats;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __invoke()
    {
        /* @var $event EventInterface */
        $event = func_get_arg(0);

        if (!($event instanceof EventInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not an event instance'), null, null, $event
            );
        }

        $this->_enqueueState();
    }

    /**
     * Enqueue application's state.
     *
     * @since [*next-version*]
     */
    protected function _enqueueState()
    {
        wp_localize_script('eddbk-wizard-app', 'EDDBK_WIZARD_APP_STATE', [
            'applicationSelector' => $this->applicationSelector,
            'apiBaseUrls' => $this->_getApiBaseUrls($this->apiBaseUrls),
            'initialBookingTransition' => $this->initialBookingTransition,
            'datetimeFormats' => $this->_normalizeArray($this->datetimeFormats),
        ]);
    }

    /**
     * Get full URLs of API resources.
     *
     * @since [*next-version*]
     *
     * @param Traversable|array|stdClass $apiBaseUrls
     *
     * @return array Full URLs of API resources.
     */
    protected function _getApiBaseUrls($apiBaseUrls)
    {
        $preparedApiBaseUrls = [];

        foreach ($apiBaseUrls as $name => $baseUrl) {
            $preparedApiBaseUrls[$name] = rest_url($baseUrl);
        }

        return $preparedApiBaseUrls;
    }
}
