<?php

namespace RebelCode\Bookings\WordPress\Module;

use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\ContainerGetPathCapableTrait;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Data\Container\ContainerHasPathCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Object\NormalizeKeyCapableTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Util\Normalization\NormalizeArrayCapableTrait;
use Dhii\Util\Normalization\NormalizeIterableCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Psr\Container\ContainerInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Class OptionsContainer.
 *
 * @since [*next-version*]
 *
 * Allows to retrieve WP options using container interface.
 */
class OptionsContainer implements ContainerInterface
{
    /** @since [*next-version*] */
    use ContainerGetPathCapableTrait;

    /** @since [*next-version*] */
    use ContainerHasPathCapableTrait;

    /** @since [*next-version*] */
    use ContainerGetCapableTrait;

    /** @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /** @since [*next-version*] */
    use CreateContainerExceptionCapableTrait;

    /** @since [*next-version*] */
    use NormalizeArrayCapableTrait;

    /** @since [*next-version*] */
    use CreateNotFoundExceptionCapableTrait;

    /** @since [*next-version*] */
    use NormalizeIterableCapableTrait;

    /** @since [*next-version*] */
    use ContainerHasCapableTrait;

    /** @since [*next-version*] */
    use NormalizeKeyCapableTrait;

    /** @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /** @since [*next-version*] */
    use StringTranslatingTrait;

    /**
     * @var Stringable|string Delimiter of path.
     *
     * @since [*next-version*]
     */
    protected $delimiter;

    /**
     * OptionsContainer constructor.
     *
     * @since [*next-version*]
     *
     * @param Stringable|string $delimiter Delimiter of path.
     */
    public function __construct($delimiter = '/')
    {
        $this->delimiter = $delimiter;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        $segments = $this->_getSegmentsFromPath($id);
        $optionName = array_shift($segments);

        $option = get_option($optionName);

        return $this->_containerGetPath($option, $segments);
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function has($id)
    {
        $segments = $this->_getSegmentsFromPath($id);
        $optionName = array_shift($segments);

        if (!$option = get_option($optionName)) {
            return false;
        }

        return $this->_containerHasPath($option, $segments);
    }

    /**
     * Get list of segments from path with delimiters.
     *
     * @since [*next-version*]
     *
     * @param Stringable|string $path Path that should be exploded to segments.
     *
     * @return string[] Array of segments.
     */
    protected function _getSegmentsFromPath($path)
    {
        $path = $this->_normalizeString($path);
        return explode($this->delimiter, $path);
    }
}
