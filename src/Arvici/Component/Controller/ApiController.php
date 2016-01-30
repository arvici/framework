<?php
/**
 * Api Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Component\Controller;

/**
 * Api Controller
 * @package Arvici\Component\Controller
 */
abstract class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}
