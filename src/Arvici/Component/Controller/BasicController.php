<?php
/**
 * Basic Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component\Controller;


use Arvici\Component\View\Builder;
use Arvici\Component\View\View;

/**
 * Basic Controller - Use for view's.
 *
 * @package Arvici\Component\Controller
 */
abstract class BasicController extends Controller
{
    /**
     * @var Builder $view View builder
     */
    protected $view;

    public function __construct()
    {
        parent::__construct();

        $this->view = View::build()->defaultStack();
    }
}
