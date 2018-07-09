<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Data\Container\ContainerSetCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateRuntimeExceptionCapableTrait;
use Dhii\I18n\StringTranslatorAwareTrait;
use Dhii\I18n\StringTranslatorConsumingTrait;
use Dhii\Output\AbstractBasePlaceholderTemplate;
use Dhii\Regex\GetAllMatchesRegexCapablePcreTrait;
use Dhii\Regex\QuoteRegexCapablePcreTrait;
use Dhii\Util\String\StringableReplaceCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;
use stdClass;
use Traversable;

/**
 * A template implementation that replaces placeholders in text
 * and casts some context fields to JSON-encoded strings.
 *
 * @since [*next-version*]
 */
class JsonStringPlaceholderTemplate extends AbstractBasePlaceholderTemplate
{
    /* @since [*next-version*] */
    use GetAllMatchesRegexCapablePcreTrait;

    /* @since [*next-version*] */
    use QuoteRegexCapablePcreTrait;

    /* @since [*next-version*] */
    use StringableReplaceCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatorConsumingTrait;

    /* @since [*next-version*] */
    use StringTranslatorAwareTrait;

    /* @since [*next-version*] */
    use CreateContainerExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateNotFoundExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateRuntimeExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /* @since [*next-version*] */
    use ContainerGetCapableTrait;

    /* @since [*next-version*] */
    use ContainerHasCapableTrait;

    /* @since [*next-version*] */
    use ContainerSetCapableTrait;

    /**
     * List of fields that should be rendered as JSON strings.
     *
     * @since [*next-version*]
     *
     * @var array|stdClass|Traversable
     */
    protected $jsonStringFields;

    /**
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $template         The template which is represented by this instance.
     * @param Stringable|string|int|float|bool $tokenStart       The delimiter which represents the start of a token.
     * @param Stringable|string|int|float|bool $tokenEnd         The delimiter which represents the end of a token.
     * @param Stringable|string|int|float|bool $tokenDefault     The default value to use when a token's value cannot be resolved.
     * @param array|stdClass|Traversable       $jsonStringFields List of fields that should be rendered as JSON-encoded strings.
     *
     * @throws InvalidArgumentException If one of the parameters is of an invalid type.
     */
    public function __construct($template, $tokenStart, $tokenEnd, $tokenDefault, $jsonStringFields)
    {
        $this->_setPlaceholderTemplate($template);
        $this->_setTokenStart($tokenStart);
        $this->_setTokenEnd($tokenEnd);
        $this->_setDefaultPlaceholderValue($tokenDefault);

        $this->jsonStringFields = $jsonStringFields;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function render($context = null)
    {
        if ($context) {
            foreach ($this->jsonStringFields as $jsonStringField) {
                if (!$this->_containerHas($context, $jsonStringField)) {
                    continue;
                }

                $this->_containerSet($context, $jsonStringField, json_encode($this->_containerGet($context, $jsonStringField)));
            }
        }

        return parent::render($context);
    }
}
