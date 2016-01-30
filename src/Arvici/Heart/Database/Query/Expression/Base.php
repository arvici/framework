<?php
/**
 * Base Expression Class.
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Database\Query\Expression;

use Arvici\Exception\ExpressionBuilderException;

/**
 * Class Base Expression
 *
 * @package Arvici\Heart\Database\Query\Expression
 */
abstract class Base
{
    protected $preSeparator = "(";
    protected $separator = ", ";
    protected $postSeparator = ")";

    protected $allowedClasses = array();

    protected $parts = array();

    public function __construct($args = array())
    {
        $this->addAll($args);
    }

    public function addAll($all = array())
    {
        foreach ($all as $item) {
            $this->add($item);
        }
    }

    public function add($item)
    {
        if ($item !== null && $item instanceof self && $item->count() > 0) {
            if (! is_string($item)) {
                $clazz = get_class($item);
                if (! in_array($clazz, $this->allowedClasses)) {
                    throw new ExpressionBuilderException("Class not allowed here! ('".$clazz."')");
                }
            }

            // Add to parts.
            $this->parts[] = $item;
        }
    }

    public function count()
    {
        return count($this->parts);
    }

    public function __toString()
    {
        if ($this->count() === 1) {
            return (string)$this->parts[0];
        }

        return $this->preSeparator
             . implode($this->separator, $this->parts)
             . $this->postSeparator;
    }
}