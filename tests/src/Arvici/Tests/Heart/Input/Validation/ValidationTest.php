<?php
/**
 * ValidationTest
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Tests\Heart\Input\Validation;

use Arvici\Heart\Input\Validation\Assert\FloatType;
use Arvici\Heart\Input\Validation\Assert\IntegerType;
use Arvici\Heart\Input\Validation\Validation;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidationTest
 * @package Arvici\Tests\Heart\Input\Validation
 *
 * @covers \Arvici\Heart\Input\Validation\Validation
 * @covers \Arvici\Heart\Input\Validation\Assert
 * @covers \Arvici\Heart\Input\Validation\Assert\Collection
 */
class ValidationTest extends TestCase
{

    public function testValidationBasics()
    {
        $input = array('first' => 5, 'second' => false, 'third' => 3, 'fourth' => 'Invalid90(*&');

        // Load set
        $validator = new Validation($input);
        $validator->loadSet('testSet');

        $result = $validator->run();

        $this->assertFalse($result);

        $errors = $validator->getErrors();
        $this->assertCount(1, $errors);

        $errorString = $validator->getErrorMessage();
        $this->assertContains("fourth", $errorString);



        // Valid test
        $validator = new Validation($input);
        $validator->setConstraints([
            'first' => new IntegerType()
        ]);

        $result = $validator->run();

        $this->assertNotFalse($result);
        $this->assertEquals($input, $result);
        $this->assertFalse($validator->getErrors());
        $this->assertEquals("", $validator->getErrorMessage());


        // Invalid test with more function testing on validator
        $validator = new Validation($input);
        $validator->setConstraints([
            'first' => new IntegerType()
        ]);

        $validator->addConstraint('second', new FloatType());

        $validator->addConstraints([
            'fourth' => new IntegerType(),
            'third' => new IntegerType()
        ]);

        $result = $validator->run();

        $this->assertFalse($result);
    }
}
