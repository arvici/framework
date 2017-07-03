<?php

use Arvici\Heart\Config\Configuration;
use Arvici\Component\View\View;

/**
 * Template Configuration
 */
Configuration::define('template', function() {
    return [
        /**
         * Template Path, relative to the App path.
         * Don't add a trailing slash at the end!
         */
        'templatePath' => 'Template/Default',

        /**
         * View Path, used for body views, relative to App path.
         * Don't add a trailing slash at the end!
         */
        'viewPath' => 'View',

        /**
         * The default stack for template rendering.
         * Use the body placeholder for defining the body part.
         */
        'defaultStack' => [
            View::template('header'),
            View::bodyPlaceholder(),
            View::template('footer')
        ],

        /**
         * The default template engine.
         * Could be:    PhpTemplate
         *              MustacheTemplate
         *
         */
        'defaultEngine' => 'PhpTemplate',

        /**
         * Define custom prepared stacks here.
         */
        'stacks' => [
            'test-sample' => [
                View::template('testHeader'),
                View::bodyPlaceholder(),
                View::body('testContent'),
                View::template('testFooter')
            ],
            'test-basicrender' => [
                View::template('testHeader'),
                View::bodyPlaceholder(),
                View::template('testFooter')
            ]
        ],
    ];
});
