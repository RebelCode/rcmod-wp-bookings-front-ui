<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Collection\MapInterface;
use Dhii\Event\EventFactoryInterface;
use Dhii\Output\BlockInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\EventManager\EventManagerInterface;
use RebelCode\Modular\Events\EventsConsumerTrait;
use stdClass;

/**
 * Class for showing wizard and enqueuing all assets that are related to it.
 *
 * @since [*next-version*]
 */
class WizardBlock implements BlockInterface
{
    /* @since [*next-version*] */
    use EventsConsumerTrait;

    /**
     * Is assets attached already.
     *
     * @since [*next-version*]
     *
     * @var bool
     */
    protected static $isAssetsAttached = false;

    /**
     * Wizard template.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $wizardTemplate;

    /**
     * Context of wizard template.
     *
     * @since [*next-version*]
     *
     * @var array|MapInterface|stdClass
     */
    protected $context;

    /**
     * Components templates.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $componentsTemplates;

    /**
     * WizardBlock constructor.
     *
     * @since [*next-version*]
     *
     * @param array|stdClass|MapInterface $context             Context of wizard template.
     * @param TemplateInterface           $wizardTemplate      Wizard template.
     * @param string|Stringable           $componentsTemplates Components templates.
     * @param EventManagerInterface       $eventManager        The event manager.
     * @param EventFactoryInterface       $eventFactory        The event factory.
     */
    public function __construct(
        $context,
        $wizardTemplate,
        $componentsTemplates,
        $eventManager,
        $eventFactory
    ) {
        $this->wizardTemplate      = $wizardTemplate;
        $this->context             = $context;
        $this->componentsTemplates = $componentsTemplates;

        $this->_setEventManager($eventManager);
        $this->_setEventFactory($eventFactory);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function render()
    {
        if (!static::$isAssetsAttached) {
            static::$isAssetsAttached = true;
            $this->_trigger('eddbk_wizard_enqueue_assets');
            $this->_trigger('eddbk_wizard_enqueue_app_state');
            $this->_attach('wp_footer', function () {
                echo $this->componentsTemplates;
            });
        }

        return $this->wizardTemplate->render($this->context);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return $this->render();
    }
}
