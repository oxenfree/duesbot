<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
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
     * @Route("/", name="app_index")
     */
    public function indexAction(Request $request)
    {
        $template = 'default/index.html.twig';
        $renderParams = [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'allUsers' => $this->get('fos_user.user_manager')->findUsers(),
            'allClubs' => $this->getDoctrine()->getRepository(Club::class)->findAll(),
            'allEvents' => $this->getDoctrine()->getRepository(Event::class)->findAll(),
        ];
        $checker = $this->get('security.authorization_checker');

        if ($checker->isGranted('ROLE_USER'))   {
            unset($template);
            $template = 'default/index_logged_in.html.twig';
            $currentUser = $this->getUser();
            $bcEast = $this
                ->getDoctrine()
                ->getRepository(Club::class)
                ->findOneBy(['name' => 'Bat Country East']);
            $club = $currentUser->getClub();
            if(!isset($club))  {
                $currentUser->setClub($bcEast);
                $club = $bcEast;
            };
            $renderParams = [
                'user' => $currentUser,
                'club' => $club,
                'events' => $club->getEvents(),
            ];
        }

        return $this->render($template, $renderParams);
    }
}
