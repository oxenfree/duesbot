<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/7/17
 * Time: 7:48 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Due;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     *
     * @return Response|RedirectResponse
     */
    public function profileShowAction()
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

    /**
     * @Route("/{id}", name="app_other_show")
     * @param int $id
     *
     * @return Response|RedirectResponse
     */
    public function otherShowAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $this->addFlash('success', 'Sorry, you have to log in first.');

            return $this->redirectToRoute('app_index');
        }
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $dues = $this->getDoctrine()->getRepository(Due::class)->findBy(['user' => $user]);

        return $this->render('/user/show.html.twig', [
            'user' => $user,
            'dues' => $dues,
            'events' => $user->getEvents(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit")
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $viewer = $this->getUser();

        if (!(
            $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ||
            $user == $viewer
        )) {
            $this->addFlash('success', 'Sorry, you can\'t edit that page.');

            return $this->redirectToRoute('app_index');
        }

        $editForm = $this->createForm(UserEditType::class, $user);
        $editForm->remove('roles');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('/user/edit.html.twig', [
            'form' => $editForm->createView(),
            'user' => $user,
        ]);
    }
}
