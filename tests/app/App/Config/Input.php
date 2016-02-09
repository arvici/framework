<?php
use Arvici\Heart\Config\Configuration as Configure;


/**
 * Config: input
 */
Configure::define('input', function () {
    return [

        /**
         * Input - Validation
         * Validation sets.
         */
        'validationSets' => [
            'testSet' => [
                'first' => new \Arvici\Heart\Input\Validation\Assert\IntegerType(),
                'second' => new \Arvici\Heart\Input\Validation\Assert\Collection([
                    new \Arvici\Heart\Input\Validation\Assert\Required(),
                    new \Arvici\Heart\Input\Validation\Assert\BooleanType()
                ]),
                'third' => null, // Optional
                'fourth' => new \Arvici\Heart\Input\Validation\Assert\Collection([
                    new \Arvici\Heart\Input\Validation\Assert\Alpha(false, false),
                    new \Arvici\Heart\Input\Validation\Assert\Required()
                ])
            ],
        ],

    ];
});
