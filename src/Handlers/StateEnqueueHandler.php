<?php

namespace RebelCode\Bookings\WordPress\Module\Handlers;

use Dhii\Collection\MapInterface;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Invocation\InvocableInterface;
use Dhii\Util\Normalization\NormalizeArrayCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\EventManager\EventInterface;
use stdClass;

/**
 * Handle state output for the client application.
 *
 * @since [*next-version*]
 */
class StateEnqueueHandler implements InvocableInterface
{
    /* @since [*next-version*] */
    use NormalizeArrayCapableTrait;

    /* @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatingTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /**
     * State variable name.
     *
     * @since [*next-version*]
     *
     * @var Stringable|string
     */
    protected $stateVarName;

    /**
     * Application container's CSS selector.
     *
     * @since [*next-version*]
     *
     * @var Stringable|string
     */
    protected $applicationSelector;

    /**
     * Map of endpoint name to its API url.
     *
     * @since [*next-version*]
     *
     * @var array|MapInterface|stdClass
     */
    protected $apiEndpointUrls;

    /**
     * Name of initial transition for booking.
     *
     * @since [*next-version*]
     *
     * @var Stringable|string
     */
    protected $initialBookingTransition;

    /**
     * Map of additional booking fields to their aliases for client.
     *
     * @since [*next-version*]
     *
     * @var MapInterface|array|stdClass
     */
    protected $bookingDataMap;

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
     * @param string|Stringable           $stateVarName             State variable name.
     * @param string|Stringable           $applicationSelector      Application container's CSS selector.
     * @param MapInterface|array|stdClass $apiEndpointUrls          Map of endpoint name to its API url.
     * @param MapInterface|array|stdClass $bookingDataMap           Map of additional booking fields to their aliases for client.
     * @param string|Stringable           $initialBookingTransition Name of initial transition for booking.
     * @param MapInterface|array|stdClass $datetimeFormats          List of datetime formats for application.
     */
    public function __construct(
        $stateVarName,
        $applicationSelector,
        $apiEndpointUrls,
        $bookingDataMap,
        $initialBookingTransition,
        $datetimeFormats
    ) {
        $this->stateVarName             = $stateVarName;
        $this->applicationSelector      = $this->_normalizeString($applicationSelector);
        $this->apiEndpointUrls          = $apiEndpointUrls;
        $this->bookingDataMap           = $bookingDataMap;
        $this->initialBookingTransition = $this->_normalizeString($initialBookingTransition);
        $this->datetimeFormats          = $datetimeFormats;
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
        wp_localize_script('eddbk-wizard-app', $this->stateVarName, [
            'applicationSelector'      => $this->applicationSelector,
            'apiEndpointUrls'          => $this->_getApiEndpointUrls($this->apiEndpointUrls),
            'bookingDataMap'           => $this->_normalizeArray($this->bookingDataMap),
            'initialBookingTransition' => $this->initialBookingTransition,
            'datetimeFormats'          => $this->_normalizeArray($this->datetimeFormats),
        ]);
    }

    /**
     * Get full URLs of API endpoints.
     *
     * @since [*next-version*]
     *
     * @param MapInterface|array|stdClass $apiEndpointUrls Map of endpoint name to its API url.
     *
     * @return array Full URLs of API resources.
     */
    protected function _getApiEndpointUrls($apiEndpointUrls)
    {
        $preparedApiEndpointUrls = [];

        foreach ($apiEndpointUrls as $name => $baseUrl) {
            $preparedApiEndpointUrls[$name] = rest_url($baseUrl);
        }

        return $preparedApiEndpointUrls;
    }
}
