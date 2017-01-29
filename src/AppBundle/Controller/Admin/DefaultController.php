<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Club;
use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_admin_index")
     */
    public function indexAction(Request $request)
    {
        $users = $this->get('fos_user.user_manager')->findUsers();
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        // replace this example code with whatever you need
        return $this->render('Admin/default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'users' => $users,
            'clubs' => $clubs,
            'events' => $events,
        ]);
    }
}
