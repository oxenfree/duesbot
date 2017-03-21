<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        if ($checker->isGranted('ROLE_USER'))   {

            return $this->redirectToRoute('app_club_index');
        }

        return $this->render('default/index.html.twig');
    }
}
