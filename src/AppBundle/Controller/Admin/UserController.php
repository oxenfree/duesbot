<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/26/17
 * Time: 7:07 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package AppBundle\Controller\Admin
 *
 * @Route("/member")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="app_admin_user_list_all")
     *
     * @return Response
     */
    public function listUser()
    {
        $um = $this->get('fos_user.user_manager');
        $users = $um->findUsers();

        return $this->render('Admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/create", name="app_admin_user_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createUser(Request $request)
    {
        $user = new User();
        $bcEast = $this->getDoctrine()->getRepository(Club::class)->find(1);
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $user->setClub($bcEast);
            $um = $this->get('fos_user.user_manager');
            $um->updateUser($user);

            return $this->redirectToRoute('app_admin_user_list_all');
        }

        return $this->render('Admin/user/new.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_user_show")
     *
     * @param User $user
     *
     * @return Response
     */
    public function showUser(User $user)
    {
       return$this->render('Admin/user/show.html.twig', [
           'user' => $user,
       ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_user_edit")
     *
     * @param User $user
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $um = $this->get('fos_user.user_manager');
            $um->updateUser($user);

            return $this->redirectToRoute('app_admin_user_list_all');
        }

        return $this->render('Admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_user_delete")
     *
     * @param User $user
     *
     *@return RedirectResponse
     */
    public function deleteUser(User $user)
    {
        $um = $this->get('fos_user.user_manager');
        $um->deleteUser($user);

        return $this->redirectToRoute('app_admin_user_list_all');
    }
}
