<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 3/20/17
 * Time: 7:37 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ClubController
 * @package AppBundle\Controller
 * @Route("/club")
 */
class ClubController extends Controller
{
    /**
     * @Route("/", name="app_club_index")
     *
     * @return Response
     */
    public function clubIndexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getClub() != null) {

            return $this->redirectToRoute('app_club_show', ['club' => $user->getClub()->getId()]);
        }

        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();

        return $this->render('club/index.html.twig', [
            'user' => $user,
            'clubs' => $clubs
        ]);
    }

    /**
     * @Route("/{club}", name="app_club_show")
     * @param Club $club
     *
     * @return Response
     */
    public function clubShowAction(Club $club = null)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (null == $club || null == $user->getClub()) {

            return $this->redirectToRoute('app_club_index');
        }


        if ($user->getClub() !== $club) {

            return $this->redirectToRoute('app_club_show', ['club' => $user->getClub()->getId()]);
        }

        $clubService = $this->get('club_service');
        $eventCosts = $clubService->checkEventCosts($club);
        $clubDues = $clubService->getCurrentDues($club);
        $dues = $this->get('dues_service')->getDuesForUser($user);

        return $this->render('club/show.html.twig', [
            'user' => $user,
            'club' => $club,
            'events' => $club->getEvents(),
            'clubDues' => $clubDues,
            'dues' => $dues,
            'eventCosts' => $eventCosts,
        ]);
    }

    /**
     * @Route("/join/{club}", name="app_club_join")
     * @Method({"GET", "POST"})
     *
     * @param Club $club
     * @return RedirectResponse
     */
    public function clubJoinAction(Club $club)
    {
        /** @var User $user */
        $user = $this->getUser();
        $club->addUser($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_club_show', ['club' => $club->getId()]);
    }
}