<?php

namespace RebelCode\Bookings\WordPress\Module\Handlers;

use Dhii\Invocation\InvocableInterface;
use Dhii\Output\TemplateInterface;
use Psr\EventManager\EventInterface;

/**
 * Handler for rendering main application component's container.
 *
 * @since [*next-version*]
 */
class MainComponentHandler implements InvocableInterface
{
    /**
     * Template of application holder.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $template;

    /**
     * Cart page ID.
     *
     * @since [*next-version*]
     *
     * @var int
     */
    protected $cartPageId;

    /**
     * MainComponentHandler constructor.
     *
     * @since [*next-version*]
     *
     * @param TemplateInterface $template   Template of application holder.
     * @param int               $cartPageId Cart page ID.
     */
    public function __construct($template, $cartPageId)
    {
        $this->template   = $template;
        $this->cartPageId = $cartPageId;
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

        $event->setParams(array_merge($event->getParams(), [
            'content' => $this->_renderComponent($event->getParams()),
        ]));
    }

    /**
     * Render booking holder.
     *
     * @since [*next-version*]
     *
     * @param $params
     *
     * @return string
     */
    protected function _renderComponent($params = [])
    {
        $params['redirectUrl'] = $this->_getRedirectUrl();

        return $this->template->render([
            'config' => json_encode($params),
        ]);
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
}
