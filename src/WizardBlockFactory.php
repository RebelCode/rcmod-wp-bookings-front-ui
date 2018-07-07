<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Container\NormalizeKeyCapableTrait;
use Dhii\Event\EventFactoryInterface;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\Factory\FactoryInterface;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Output\TemplateInterface;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Psr\EventManager\EventManagerInterface;

/**
 * Factory for creating wizard blocks.
 *
 * @since [*next-version*]
 */
class WizardBlockFactory implements FactoryInterface
{
    /* @since [*next-version*] */
    use ContainerGetCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatingTrait;

    /* @since [*next-version*] */
    use CreateContainerExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateNotFoundExceptionCapableTrait;

    /* @since [*next-version*] */
    use NormalizeKeyCapableTrait;

    /* @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use CreateOutOfRangeExceptionCapableTrait;

    /**
     * Config key of attributes.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    const K_CFG_ATTRIBUTES = 'attributes';

    /**
     * Main application template.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $mainTemplate;

    /**
     * Rendered components templates.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected $renderedComponents;

    /**
     * The event manager.
     *
     * @since [*next-version*]
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * The event factory.
     *
     * @since [*next-version*]
     *
     * @var EventFactoryInterface
     */
    protected $eventFactory;

    /**
     * WizardBlockFactory constructor.
     *
     * @since [*next-version*]
     *
     * @param TemplateInterface     $mainTemplate       Main application template.
     * @param string                $renderedComponents Rendered components templates.
     * @param EventManagerInterface $eventManager       The event manager.
     * @param EventFactoryInterface $eventFactory       The event factory.
     */
    public function __construct(
        $mainTemplate,
        $renderedComponents,
        $eventManager,
        $eventFactory
    ) {
        $this->mainTemplate       = $mainTemplate;
        $this->renderedComponents = $renderedComponents;
        $this->eventManager       = $eventManager;
        $this->eventFactory       = $eventFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return WizardBlock Configured WizardBlock instance.
     */
    public function make($config = null)
    {
        $attributes = $this->_containerGet($config, static::K_CFG_ATTRIBUTES);

        return new WizardBlock(
            $attributes,
            $this->mainTemplate,
            $this->renderedComponents,
            $this->eventManager,
            $this->eventFactory
        );
    }
}
