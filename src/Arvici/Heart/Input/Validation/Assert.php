<?php
/**
 * Assert Abstract Class
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation;


use Arvici\Exception\ValidationException;

/**
 * Assert Abstract Class. All asserts should extend this.
 *
 * @package Arvici\Heart\Input\Validation
 */
abstract class Assert
{

    private $options;

    /**
     * Construct assert with options.
     * @param array $options Options.
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * Execute assert on the field, in the data provided.
     *
     * @param array $data Full data array.
     * @param string $field Field Name.
     * @param array $options Optional options given at runtime.
     * @return bool
     *
     * @throws ValidationException
     */
    public abstract function execute(&$data, $field, $options = array());

    /**
     * Get name of assert.
     *
     * @return string
     */
    public function assertName()
    {
        return static::class;
    }

}