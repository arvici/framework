<?php
/**
 * Welcome Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Controller;

use App\Entities\SampleEntity;
use Arvici\Component\Controller\BasicController;
use Arvici\Exception\ControllerNotFoundException;
use Arvici\Heart\Database\Database;


class Welcome extends BasicController
{
    public function index()
    {
        $sample = new SampleEntity();
        $em = Database::entityManager();
        $em->persist($sample);
        $em->flush();

        $this->view->body('welcome')->data([
            'models' => $em->getRepository('App\Entities\SampleEntity')->findAll()
        ])->render();
    }

    public function session()
    {
        $this->request->session()->set('test', ['ok' => true]);
        $this->request->session()->set('test2', true);

        $this->view
            ->body('session')
            ->data(['session' => $this->request->session()->all()])
            ->render();
    }

    public function json()
    {
        $this->response->json(array(
            'first' => true,
            'second' => true,
            'multi' => array(
                'Hello!'
            )
        ))->send();
    }

    public function exception()
    {
        throw new ControllerNotFoundException("Controller provided should give an exception on this route call!");
    }
}
