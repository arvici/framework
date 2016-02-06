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
    /** @var array */
    protected $conditions;

    /** @var array */
    protected $preferences;

    /**
     * Construct assert with options.
     * @param array $conditions Options.
     * @param array $preferences Preferences for testing.
     */
    public function __construct($conditions = array(), $preferences = array())
    {
        $this->conditions = $conditions;
        $this->preferences = $preferences;
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
        return join('', array_slice(explode('\\', static::class), -1));
    }

}