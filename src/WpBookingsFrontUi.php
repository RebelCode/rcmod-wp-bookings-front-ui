<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Event\EventFactoryInterface;
use Dhii\Output\TemplateInterface;
use Psr\Container\ContainerInterface;
use Psr\EventManager\EventManagerInterface;
use RebelCode\Bookings\WordPress\Module\Handlers\AssetsEnqueueHandler;
use RebelCode\Bookings\WordPress\Module\Handlers\MainComponentHandler;
use RebelCode\Bookings\WordPress\Module\Handlers\OutputTemplateHandler;
use RebelCode\Bookings\WordPress\Module\Handlers\StateEnqueueHandler;
use RebelCode\Modular\Module\AbstractBaseModule;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Output\PlaceholderTemplateFactory;
use Dhii\Output\TemplateFactoryInterface;

class WpBookingsFrontUi extends AbstractBaseModule
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable         $key                  The module key.
     * @param string[]|Stringable[]     $dependencies         The module  dependencies.
     * @param ContainerFactoryInterface $configFactory        The config factory.
     * @param ContainerFactoryInterface $containerFactory     The container factory.
     * @param ContainerFactoryInterface $compContainerFactory The composite container factory.
     * @param EventManagerInterface     $eventManager         The event manager.
     * @param EventFactoryInterface     $eventFactory         The event factory.
     */
    public function __construct(
        $key,
        $dependencies,
        $configFactory,
        $containerFactory,
        $compContainerFactory,
        $eventManager,
        $eventFactory
    ) {
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
        return $this->_setupContainer(
            $this->_loadPhpConfigFile(WP_BOOKINGS_FRONT_UI_MODULE_CONFIG), [
                /*
                 * Templates factory.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_template_factory' => function (ContainerInterface $c) {
                    return new PlaceholderTemplateFactory(
                        'Dhii\Output\PlaceholderTemplate',
                        $c->get('bookings_front_ui/templates_config/token_start'),
                        $c->get('bookings_front_ui/templates_config/token_end'),
                        $c->get('bookings_front_ui/templates_config/token_default')
                    );
                },

                /*
                 * Template for app holder.
                 *
                 * @since [*next-version*]
                 */
                'eddbk_front_app_holder_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'application-holder.html');
                },

                /*
                 * Template that holds all components templates.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_components_templates' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/index.html');
                },

                /*
                 * Main wizard template.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_wizard_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/eddbk-wizard.html');
                },

                /*
                 * Template for confirmation step.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_confirmation_step_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/wizard-confirmation-step.html');
                },

                /*
                 * Template for selecting session step.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_session_step_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/wizard-session-step.html');
                },

                /*
                 * Template for selecting service.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_service_step_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/wizard-service-step.html');
                },

                /*
                 * Template for session picker sub-components.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_session_selector_template' => function (ContainerInterface $c) {
                    return $this->_makeTemplate($c->get('eddbk_front_template_factory'), 'components/session-selector.html');
                },

                /*
                 * Handling templates output for application.
                 *
                 * @since [*next-version*]
                 */
                'eddbk_wizard_components_templates_handler' => function (ContainerInterface $c) {
                    return new OutputTemplateHandler($c->get('eddbk_front_components_templates'), [
                        'eddbkWizardTemplate'            => $c->get('eddbk_front_wizard_template'),
                        'sessionSelectorTemplate'        => $c->get('eddbk_front_session_selector_template'),
                        'wizardServiceStepTemplate'      => $c->get('eddbk_front_service_step_template'),
                        'wizardSessionStepTemplate'      => $c->get('eddbk_front_session_step_template'),
                        'wizardConfirmationStepTemplate' => $c->get('eddbk_front_confirmation_step_template'),
                    ]);
                },

                'eddbk_wizard_assets_urls_map' => function (ContainerInterface $c) {
                    $containerFactory = $this->_getContainerFactory();
                    $assetsUrlsMap = require_once $c->get('bookings_front_ui/assets_urls_map_path');

                    return $containerFactory->make([
                        ContainerFactoryInterface::K_DATA => $assetsUrlsMap,
                    ]);
                },

                'eddbk_wizard_assets' => function (ContainerInterface $c) {
                    return $c->get('bookings_front_ui/assets');
                },

                /*
                 * Handles assets enqueuing for application.
                 *
                 * @since [*next-version*]
                 */
                'eddbk_wizard_enqueue_assets_handler' => function (ContainerInterface $c) {
                    return new AssetsEnqueueHandler($c->get('eddbk_wizard_assets_urls_map'), $c->get('eddbk_wizard_assets'));
                },

                /*
                 * Handles state output for client application.
                 *
                 * @since [*next-version*]
                 */
                'eddbk_wizard_enqueue_app_state_handler' => function (ContainerInterface $c) {
                    return new StateEnqueueHandler(
                        $c->get('bookings_front_ui/application_selector'),
                        $c->get('bookings_front_ui/api_endpoint_urls'),
                        $c->get('bookings_front_ui/booking_data_map'),
                        $c->get('bookings_front_ui/initial_booking_transition'),
                        $c->get('bookings_front_ui/formats/datetime')
                    );
                },

                /*
                 * Handles for outputting main component of application.
                 *
                 * @since [*next-version*]
                 */
                'eddbk_wizard_main_component_handler' => function (ContainerInterface $c) {
                    return new MainComponentHandler(
                        $c->get('eddbk_front_app_holder_template')
                    );
                },
            ]);
    }

    /**
     * Create template instance from file.
     *
     * @since [*next-version*]
     *
     * @param TemplateFactoryInterface $templateFactory Template factory for creating template.
     * @param string                   $templateFile    Template file for reading.
     *
     * @return TemplateInterface The created template.
     */
    protected function _makeTemplate($templateFactory, $templateFile)
    {
        $templatePath = WP_BOOKINGS_FRONT_UI_MODULE_DIR . DIRECTORY_SEPARATOR
            . 'templates' . DIRECTORY_SEPARATOR . $templateFile;

        $templateContent = file_get_contents($templatePath);

        return $templateFactory->make([
            TemplateFactoryInterface::K_TEMPLATE => $templateContent,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null)
    {
        $this->_attach('eddbk_wizard_components_templates', $c->get('eddbk_wizard_components_templates_handler'));

        $this->_attach('eddbk_wizard_enqueue_assets', $c->get('eddbk_wizard_enqueue_assets_handler'));

        $this->_attach('eddbk_wizard_enqueue_app_state', $c->get('eddbk_wizard_enqueue_app_state_handler'));

        $this->_attach('eddbk_wizard_main_component', $c->get('eddbk_wizard_main_component_handler'));
    }
}
