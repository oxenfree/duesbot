<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/21/17
 * Time: 7:47 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Club;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventStatus;
use AppBundle\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class EventController
 *
 * @package AppBundle\Controller
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/", name="app_admin_event_list_all")
     *
     * @return Response
     */
    public function listAllAction()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('Admin/event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/create", name="app_admin_event_create")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $event = new Event();
        $bcEast = $this->getDoctrine()->getRepository(Club::class)->findOneBy(['name' => 'Bat Country East']);
        $form = $this->createForm(EventEditType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $status = (new EventStatus())
                ->setValue(EventStatus::VOTING_OPEN);
            $event->setStatus($status);
            $event->setClub($bcEast);
            $event->setVotingStart(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event created!');

            return $this->redirectToRoute('app_admin_event_show', ['id' => $event->getId()]);
        }

        return $this->render('Admin/event/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_event_show")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        return $this->render('Admin/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_event_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param int       $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        $form = $this->createForm(EventEditType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event edited!');

            return $this->redirectToRoute('app_admin_event_list_all');
        }

        return $this->render('Admin/event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_event_delete")
     * @Method({"GET", "POST"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $club = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($club);
        $em->flush();
        $this->addFlash('success', 'Event deleted!');


        return $this->redirectToRoute('app_admin_event_list_all');
    }
}
