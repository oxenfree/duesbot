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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $treasuries = [];
        $members = [];
        $memberCount = 0;
        $maxMembers = 0;
        $minMembers = 0;

        foreach ($clubs as $club) {
            $treasuries[] = $club->getTreasury();
            $members [] = $club->getUsers();
        }

        foreach ($members as $memberArray) {
            $maxMembers = count($memberArray) <= $maxMembers ? $maxMembers : count($memberArray);
            $minMembers = count($memberArray) < count(next($members)) ? count($memberArray) : count(next($members));
            $memberCount += count($memberArray);
        }

        return $this->render('Admin/club/index.html.twig', [
            'clubs' => $clubs,
            'treasuries' => $treasuries,
            'total_treasuries' => array_sum($treasuries),
            'total_members' => $memberCount,
            'max' => max($treasuries),
            'max_members' => $maxMembers,
            'min_members' => $minMembers,
            'min' => min($treasuries),
            'avg' => array_sum($treasuries) / count($treasuries),
            'avg_members' => $minMembers + $maxMembers / 2
        ]);
    }

    /**
     * @Route("/create", name="app_admin_club_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm(ClubEditType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            $this->addFlash('success', 'Club created!');

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
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);

        $form = $this->createForm(ClubEditType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            $this->addFlash('success', 'Club edited!');

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
        $this->addFlash('success', 'Club deleted!');

        return $this->redirectToRoute('app_admin_club_list_all');
    }
}
