<?php
/**
 * Welcome Controller
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace App\Controller;

use App\Entity\SampleEntity;
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

        return $this->view->body('welcome')->data([
            'models' => $em->getRepository('App\Entity\SampleEntity')->findAll()
        ])->render();
    }

    public function session()
    {
        $this->request->getSession()->set('test', ['ok' => true]);
        $this->request->getSession()->set('test2', true);

        return $this->view
            ->body('session')
            ->data(['session' => $this->request->getSession()->all()])
            ->render();
    }

    public function json()
    {
        return $this->response->setcontent(json_encode([
            'first' => true,
            'second' => true,
            'multi' => [
                'Hello!'
            ]
        ]));
    }

    public function exception()
    {
        throw new ControllerNotFoundException("Controller provided should give an exception on this route call!");
    }
}
