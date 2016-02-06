<?php
/**
 * Not.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Input\Validation\Assert;


use Arvici\Exception\ValidationException;
use Arvici\Heart\Input\Validation\Assert;

class Not extends Assert
{

    /**
     * Does Not constructor.
     * @param array|Assert[]|Assert $asserts Assert(s) to not comply with. (Reverse).
     */
    public function __construct($asserts)
    {
        if (! is_array($asserts)) $asserts = array($asserts);
        parent::__construct($asserts);
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
    public function execute(&$data, $field, $options = array())
    {
        $comply = 0;

        foreach ($this->conditions as $assert) { /** @var Assert $assert */
            if (! $assert instanceof Assert) {
                continue; // Skip.
            }

            if ($assert->execute($data, $field, $options)) {
                $comply++;
            }

        }

        return $comply === 0;
    }
}