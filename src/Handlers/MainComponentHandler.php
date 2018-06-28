<?php

namespace RebelCode\Bookings\WordPress\Module\Handlers;

use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
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
    /* @since [*next-version*] */
    use StringTranslatingTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /**
     * Template of application holder.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $template;

    /**
     * MainComponentHandler constructor.
     *
     * @since [*next-version*]
     *
     * @param TemplateInterface $template Template of application holder.
     */
    public function __construct($template)
    {
        $this->template = $template;
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
     * Render bookings holder.
     *
     * @since [*next-version*]
     *
     * @param $params
     *
     * @return string
     */
    protected function _renderComponent($params = [])
    {
        return $this->template->render([
            'config' => json_encode($params),
        ]);
    }
}
