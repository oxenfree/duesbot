<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/7/17
 * Time: 7:48 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Due;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package AppBundle\Controller
 *
 * @Route("/member")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="app_profile_show")
     * @param Request $request
     *
     * @return Response
     */
    public function profileShowAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $this->addFlash('success', 'Sorry, you have to log in first.');

            return $this->redirectToRoute('app_index');
        }
        $user = $this->getUser();
        $dues = $this->getDoctrine()->getRepository(Due::class)->findBy(['user' => $user]);

        return $this->render('/user/show.html.twig', [
            'user' => $user,
            'dues' => $dues,
            'events' => $user->getEvents(),
        ]);
    }
}
