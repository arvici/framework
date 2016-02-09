<?php
/**
 * Collection of asserts.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;

use Arvici\Heart\Collections\DataCollection;
use Arvici\Heart\Input\Validation\Assert;

/**
 * Collection of asserts.
 *
 * @package Arvici\Heart\Input\Validation\Assert
 */
class Collection extends DataCollection
{

    /** @var array */
    private $errors = array();

    /** @var string|null */
    private $friendlyName;

    public function __construct($content = array())
    {
        parent::__construct($content);
    }

    /**
     * Set friendly field name.
     * @param $name
     * @return Collection
     */
    public function setFriendlyName($name)
    {
        $this->friendlyName = $name;
        return $this;
    }

    /**
     * Execute All Asserts
     *
     * @param array $data
     * @param string $field
     * @param array $options
     * @return bool Success?
     */
    public function execute(&$data, $field, $options = array())
    {
        $valid = true;

        foreach ($this->contents as $assert) {
            if ($assert instanceof Assert) {
                $assert->setName($this->friendlyName !== null ? $this->friendlyName : $field);

                // Execute
                if (! $assert->execute($data, $field, $options)) {
                    $valid = false;
                    $this->errors[] = (string)$assert;
                }
            }
        }

        return $valid;
    }


    /**
     * Get string of errors.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getError($field)
    {
        return implode("\n", $this->errors);
    }
}
