<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 3/20/17
 * Time: 7:37 PM
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ClubController
 * @package AppBundle\Controller
 */
class ClubController extends Controller
{
    /**
     * @Route("/club", name="app_club_index")
     *
     * @return Response
     */
    public function clubShowAction()
    {
        $checker = $this->get('security.authorization_checker');

        if (!$checker->isGranted('ROLE_USER'))   {

            return $this->redirect('/login');
        }

        $user = $this->getUser();
        $clubService = $this->get('club_service');
        $club = $clubService->checkClub($user);
        $eventCosts = $clubService->checkEventCosts($club);
        $balance = $this->get('stripe_manager')->getBalance();
        $dues = $this->get('dues_service')->getDuesForUser($user);

        return $this->render('club/index.html.twig', [
            'user' => $user,
            'club' => $club,
            'events' => $club->getEvents(),
            'dues' => $dues,
            'pending' => $balance['pending'],
            'available' => $balance['available'],
            'eventCosts' => $eventCosts,
        ]);
    }
}