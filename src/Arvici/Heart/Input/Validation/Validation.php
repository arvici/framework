<?php
/**
 * Validation
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation;

use Arvici\Heart\Config\Configuration;
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

    /** @var array */
    private $errors = array();

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
     *
     * @return Validation
     */
    public function loadSet($setName)
    {
        $sets = Configuration::get('input.validationSets', array());

        if (isset($sets[$setName])) {
            $this->set = $sets[$setName];
        }

        return $this;
    }

    /**
     * Set array with constraints
     *
     * @param array|Assert[]|Collection[] $constraints
     *
     * @return Validation
     */
    public function setConstraints(array $constraints)
    {
        $this->set = $constraints;
        return $this;
    }

    /**
     * Append constraints.
     *
     * @param array $constraints
     *
     * @return Validation
     */
    public function addConstraints (array $constraints)
    {
        $this->set = array_merge($this->set, $constraints);
        return $this;
    }

    /**
     * Add single constraint.
     *
     * @param $key
     * @param Assert $constraint
     *
     * @return Validation
     */
    public function addConstraint ($key, Assert $constraint)
    {
        $this->set[$key] = $constraint;
        return $this;
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

        foreach ($constraints as $field => $constraint)
        {
            $result = false;
            if ($constraint instanceof Assert || $constraint instanceof Collection) {
                $result = $constraint->execute($input, $field);
                if (! $result) {
                    $errors[$field] = $constraint->getError($field);
                }
            }

        }

        $this->errors = $errors;

        if (count($this->errors) === 0) return $input;
        return false;
    }


    /**
     * Get errors, array with field and textual presentation of error.
     *
     * @return array|bool
     */
    public function getErrors()
    {
        if (count($this->errors)) {
            return $this->errors;
        }
        return false;
    }

    /**
     * Get full textual error message, empty when there are no errors.
     *
     * @param bool $br use <br> tags? Default true.
     *
     * @return string
     */
    public function getErrorMessage($br = true)
    {
        if ($this->getErrors() === false) {
            return "";
        }
        $delimiter = ($br ? "<br>" : "\n");
        return implode($delimiter, $this->errors);
    }

}
