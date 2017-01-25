<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/21/17
 * Time: 7:47 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Club;
use AppBundle\Entity\Member;
use AppBundle\Form\MemberEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class MemberController
 *
 * @package AppBundle\Controller
 * @Route("/member")
 */
class MemberController extends Controller
{
    /**
     * @Route("/", name="app_admin_member_list_all")
     *
     * @return Response
     */
    public function listAllAction()
    {
        $members = $this->getDoctrine()->getRepository(Member::class)->findAll();

        return $this->render('Admin/member/index.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * @Route("/create", name="app_admin_member_create")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $member = new Member();
        $form = $this->createForm(MemberEditType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $encoder = $this->get('security.password_encoder');
            $encodedPass = $encoder->encodePassword($member, $member->getPlainPassword());
            $member->setPassword($encodedPass);

            $member->setClub($this->getDoctrine()->getRepository(Club::class)->find(1));
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute('app_admin_member_show', ['id' => $member->getId()]);
        }

        return $this->render('Admin/member/new.html.twig', [
            'form' => $form->createView(),
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_member_show")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $member = $this->getDoctrine()->getRepository(Member::class)->find($id);

        return $this->render('Admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_member_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param int       $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $member = $this->getDoctrine()->getRepository(Member::class)->find($id);
        $form = $this->createForm(MemberEditType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $encoder = $this->get('security.password_encoder');
            $encodedPass = $encoder->encodePassword($member, $member->getPlainPassword());
            $member->setPassword($encodedPass);
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute('app_admin_member_list_all');
        }

        return $this->render('Admin/member/edit.html.twig', [
            'form' => $form->createView(),
            'member' => $member,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_member_delete")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $member = $this->getDoctrine()->getRepository(Member::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($member);
        $em->flush();

        return $this->redirectToRoute('app_admin_member_list_all');
    }
}
