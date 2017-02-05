<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Entity\Due;
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
            // set club to bat country
            $bcEast = $this
                ->getDoctrine()
                ->getRepository(Club::class)
                ->findOneBy(['name' => 'Bat Country East']);
            $currentUser->setClub($bcEast);

            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();
            $balanceObj = $this->get('stripe_manager')->getBalance();
            $pending = $balanceObj['pending'][0]['amount'] / 100;
            $available = $balanceObj['available'][0]['amount'] / 100;

            $dues = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository(Due::class)
                ->findOneBy(['user' => $currentUser, 'club' => $currentUser->getClub()])
            ;

            $renderParams = [
                'user' => $currentUser,
                'club' => $currentUser->getClub(),
                'events' => $currentUser->getClub()->getEvents(),
                'dues' => $dues,
                'available' => $available,
                'pending' => $pending,
            ];
        }

        return $this->render($template, $renderParams);
    }
}
