<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_index")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $checker = $this->get('security.authorization_checker');
        $user = $this->getUser();

        if ($checker->isGranted('ROLE_USER')) {
            if ($user && null != $user->getClub()) {

                return $this->redirectToRoute('app_club_show', ['club' => $user->getClub()->getId()]);
            }

            return $this->redirectToRoute('app_club_index');
        }

        return $this->render('default/index.html.twig');
    }
}
