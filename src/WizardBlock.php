<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Collection\MapInterface;
use Dhii\Event\EventFactoryInterface;
use Dhii\Output\BlockInterface;
use Dhii\Output\TemplateInterface;
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
     * Main application template.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface
     */
    protected $mainTemplate;

    /**
     * List of attributes for wizard.
     *
     * @since [*next-version*]
     *
     * @var array|MapInterface|stdClass
     */
    protected $attributes;

    /**
     * Rendered components templates.
     *
     * @since [*next-version*]
     *
     * @var string
     */
    protected $renderedComponents;

    /**
     * WizardBlock constructor.
     *
     * @since [*next-version*]
     *
     * @param array|stdClass|MapInterface $attributes         List of attributes for wizard.
     * @param TemplateInterface           $mainTemplate       Main application template.
     * @param string                      $renderedComponents Rendered components templates.
     * @param EventManagerInterface       $eventManager       The event manager.
     * @param EventFactoryInterface       $eventFactory       The event factory.
     */
    public function __construct(
        $attributes,
        $mainTemplate,
        $renderedComponents,
        $eventManager,
        $eventFactory
    ) {
        $this->mainTemplate       = $mainTemplate;
        $this->attributes         = $attributes;
        $this->renderedComponents = $renderedComponents;

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
                echo $this->renderedComponents;
            });
        }

        return $this->mainTemplate->render([
            'config' => json_encode($this->attributes),
        ]);
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
