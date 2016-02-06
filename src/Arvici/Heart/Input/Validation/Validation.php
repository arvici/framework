<?php
/**
 * Validation
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation;

use Arvici\Heart\Http\Http;
use Arvici\Heart\Input\Validation\Assert\Collection;

/**
 * Input Validation
 * @package Arvici\Heart\Input
 */
class Validation
{

    private $input;

    /** @var array|Assert[]|Collection[] */
    private $set;

    /**
     * Create validation class with input.
     *
     * @param array|null $input Array of input, null or leave undefined for all request parameters/values.
     */
    public function __construct($input = null)
    {
        // Default we validate $_POST and $_GET data together.
        if ($input === null) $input = Http::getInstance()->request()->params();
        $this->input = $input;
        $this->set = array();
    }


    /**
     * Load defined constraint set from Configuration.
     *
     * @param string $setName
     */
    public function loadSet($setName)
    {
        // TODO: Implement set loading from configuration.
    }

    public function setConstraints(array $constraints)
    {
        $this->set = $constraints;
    }

    /**
     * Run the validators, Validate all input.
     *
     * @param array $input Optional (additional) input.
     * @param array $constraints Optional (additional) constraints.
     *
     * @return array|false Return valid input when successful, false on error.
     */
    public function run($input = array(), $constraints = array())
    {
        // Merge extra provided params.
        $input =        array_merge($this->input, $input);
        $constraints =  array_merge($this->set, $constraints);

        $errors = array();

        foreach ($this->set as $field => $constraint)
        {
            $result = false;

            // TODO: Make errors save to the class.

            if ($constraint instanceof Assert) { // Execute
                $result = $constraint->execute($input, $field);
                if (! $result) {
                    // $errors[$field] = $constraint->getError();
                }
            }

            if ($constraint instanceof Collection) { // Execute
                $result = $constraint->executeAll($input, $field);
                if (! $result) {
                    //$errors[$field] = $constraint->getAllErrors();
                }
            }

        }

        if (count($errors) === 0) return $input;
        return false;
    }


    public function getErrors()
    {
        // TODO: Implement getErrors method.
    }

}