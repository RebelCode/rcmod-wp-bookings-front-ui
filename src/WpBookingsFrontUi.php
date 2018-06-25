<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Event\EventFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\EventManager\EventManagerInterface;
use RebelCode\Modular\Module\AbstractBaseModule;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Output\PlaceholderTemplateFactory;
use Dhii\Output\TemplateFactoryInterface;

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
            $this->_loadPhpConfigFile(WP_BOOKINGS_FRONT_UI_MODULE_CONFIG), [
                'wp_bookings_front_ui' => function () {
                    return $this;
                },

                /**
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

                /**
                 * Template that holds all components templates.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_components_templates' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/index.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },

                /**
                 * Main wizard template.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_wizard_template' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/eddbk-wizard.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },

                /**
                 * Template for confirmation step.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_confirmation_step_template' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/wizard-confirmation-step.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },

                /**
                 * Template for selecting session step.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_session_step_template' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/wizard-session-step.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },

                /**
                 * Template for selecting service.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_service_step_template' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/wizard-service-step.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },

                /**
                 * Template for session picker sub-components.
                 * 
                 * @since [*next-version*]
                 */
                'eddbk_front_session_selector_template' => function (ContainerInterface $c) {
                    $template = $this->_openTemplate('templates/components/session-selector.html');
                    return $c->get('eddbk_front_template_factory')->make([
                        TemplateFactoryInterface::K_TEMPLATE => $template
                    ]);
                },
            ]);
    }

    /**
     * Get content of template file.
     * 
     * @since [*next-version*]
     * 
     * @param string $templateFile Template file for reading.
     * 
     * @return string Content of given template file.
     */
    protected function _openTemplate($templateFile) 
    {
        $templatePath = WP_BOOKINGS_FRONT_UI_MODULE_DIR . DIRECTORY_SEPARATOR . $templateFile;
        return file_get_contents($templatePath);
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
        $this->cartPageId = $c->get('bookings_front_ui/edd_settings/purchase_page');
    }

    /**
     * Render booking holder.
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

    public function enqueueAssetsIf($c, $condition) 
    {
        $this->eventManager->attach('wp_enqueue_scripts', function () use ($c, $condition) {
            if (!$condition()) {
                return;
            }
            $this->_enqueueAssets($c);
        });

        $this->eventManager->attach('wp_footer', function () use ($c, $condition) {
            if (!$condition()) {
                return;
            }
            echo $this->_getComponentsTemplates($c);
        });
    }

    /**
     * Add WP styles and scripts enqueuing.
     *
     * @since [*next-version*]
     */
    protected function _enqueueAssets($c)
    {
        wp_enqueue_script(WP_BOOKINGS_FRONT_UI_MODULE_KEY . '-main', $c->get('bookings_front_ui/main_script'), [], false, true);
        wp_enqueue_script(WP_BOOKINGS_FRONT_UI_MODULE_KEY, $c->get('bookings_front_ui/script'), [], false, true);
        wp_enqueue_style(WP_BOOKINGS_FRONT_UI_MODULE_KEY, $c->get('bookings_front_ui/style'));
    }

    /**
     * Get front ui application templates.
     * 
     * @since [*next-version*]
     * 
     * @param ContainerInterface $c The container.
     * 
     * @return string Rendered front ui application template.
     */
    protected function _getComponentsTemplates($c)
    {
        $mainTemplate = $c->get('eddbk_front_components_templates');

        $componentTemplatesContext = [
            'eddbkWizardTemplate' => $c->get('eddbk_front_wizard_template')->render(),
            'sessionSelectorTemplate' => $c->get('eddbk_front_session_selector_template')->render(),
            'wizardServiceStepTemplate' => $c->get('eddbk_front_service_step_template')->render(),
            'wizardSessionStepTemplate' => $c->get('eddbk_front_session_step_template')->render(),
            'wizardConfirmationStepTemplate' => $c->get('eddbk_front_confirmation_step_template')->render(),
        ];

        return $mainTemplate->render($componentTemplatesContext);
    }
}