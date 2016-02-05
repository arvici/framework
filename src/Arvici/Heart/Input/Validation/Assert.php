<?php
/**
 * Assert Interface
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation;


use Arvici\Exception\ValidationException;

/**
 * Assert interface. All asserts should implement this.
 *
 * @package Arvici\Heart\Input\Validation
 */
interface Assert
{
    /**
     * Execute assert.
     *
     * @param mixed $value Value to test.
     * @param array $data Full data array.
     * @param array $options Optional options given at runtime.
     * @return bool
     *
     * @throws ValidationException
     */
    public function execute(&$value, &$data, $options = array());

    /**
     * Get name of assert.
     *
     * @return string
     */
    public function assertName();

}