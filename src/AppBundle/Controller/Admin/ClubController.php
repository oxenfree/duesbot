<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/21/17
 * Time: 7:47 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Club;
use AppBundle\Form\ClubEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ClubController
 *
 * @package AppBundle\Controller
 * @Route("/club")
 */
class ClubController extends Controller
{
    /**
     * @Route("/", name="app_admin_club_list_all")
     *
     * @return Response
     */
    public function listAllAction()
    {
        $clubs = $this->getDoctrine()->getRepository(Club::class)->findAll();

        return $this->render('Admin/club/index.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    /**
     * @Route("/create", name="app_admin_club_create")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm(ClubEditType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('app_admin_club_show', ['id' => $club->getId()]);
        }

        return $this->render('Admin/club/new.html.twig', [
            'form' => $form->createView(),
            'club' => $club,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_club_show")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        return $this->render('Admin/club/show.html.twig', [
            'club' => $club,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_club_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param int       $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        $form = $this->createForm(ClubEditType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('app_admin_club_list_all');
        }

        return $this->render('Admin/club/edit.html.twig', [
            'form' => $form->createView(),
            'club' => $club,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_club_delete")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($club);
        $em->flush();

        return $this->redirectToRoute('app_admin_club_list_all');
    }
}
