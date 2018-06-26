<?php

namespace RebelCode\Bookings\WordPress\Module\Handlers;

use Dhii\Invocation\InvocableInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface;

/**
 * Handles template creation for wizard application.
 *
 * @since [*next-version*]
 */
class OutputTemplateHandler implements InvocableInterface
{
    /**
     * Main template in which all components templates will be rendered.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $mainTemplate;

    /**
     * List of components templates that should be rendered in main template.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface[]
     */
    protected $pureComponentsTemplates;

    /**
     * OutputTemplateHandler constructor.
     *
     * @since [*next-version*]
     *
     * @param TemplateInterface $mainTemplate Main template in which all components templates will be rendered.
     * @param TemplateInterface[] $pureComponentsTemplates List of components templates that should be rendered in main template.
     */
    public function __construct($mainTemplate, $pureComponentsTemplates)
    {
        $this->mainTemplate = $mainTemplate;
        $this->pureComponentsTemplates = $pureComponentsTemplates;
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

        $event->setParams([
            'content' => $this->_renderMainTemplate()
        ]);
    }

    /**
     * Render main template.
     *
     * @since [*next-version*]
     *
     * @return StringableInterface|string Rendered content of main template.
     */
    protected function _renderMainTemplate()
    {
        $componentTemplatesContext = [];

        foreach ($this->pureComponentsTemplates as $key => $pureComponentsTemplate) {
            $componentTemplatesContext[$key] = $pureComponentsTemplate->render();
        }

        return $this->mainTemplate->render($componentTemplatesContext);
    }
}