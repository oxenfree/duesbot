<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/28/17
 * Time: 2:54 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventStatus;
use AppBundle\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController
 *
 * @package AppBundle\Controller
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/{id}", name="app_event_show")
     * @Method({"GET"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function showEvent($id)
    {
        $user = $this->getUser();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $userVotes = $event->getUserVotes();
        $voteYes = null;
        $voteNo = null;

        // sets variables used on the front end
        // to disable voting buttons
        foreach ($userVotes as $key => $userVote) {
            if ($this->getUser()->getUsername() == $userVote->getUser()->getUsername()) {
                $voteYes = $userVote->getVoteYes() ? true : false;
                $voteNo = !$voteYes;
            }
        }

        $status = $event->getStatus();

        // this is a tricky one. if voting is closed both votes are set to true
        // so that each vote button will be disabled --just leveraging existing logic
        // on the front end
        if ($status->getValue() == EventStatus::VOTING_CLOSED)  {
            $voteYes = true;
            $voteNo = true;
        }

        $voteTotal = $event->getVoteTotal();

        return $this->render('/event/show.html.twig', [
            'user' => $user,
            'event' => $event,
            'userVotes' => $userVotes,
            'voteTotal' => $voteTotal,
            'voteYes' => $voteYes, // voting button variable
            'voteNo' => $voteNo, // voting button variable
        ]);
    }

    /**
     * @Route("/{id}/yep", name="app_event_vote_yes")
     * @Method({"GET"})
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function voteYes($id)
    {
        $event = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->find($id)
        ;
        $user = $this->getUser();

        $userVote = $this
            ->get('event_manager')
            ->vote($user, $event, true)
        ;

        if (null == $userVote)  {
            throw new Exception('Uservote is null.');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($userVote);
        $em->flush();
        $this->addFlash('success', 'Thanks for voting!');

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/{id}/no", name="app_event_vote_no")
     * @Method({"GET"})
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function voteNo($id)
    {
        $event = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->find($id)
        ;
        $user = $this->getUser();
        $userVote = $this
            ->get('event_manager')
            ->vote($user, $event, false)
        ;

        if (null == $userVote)  {
            throw new Exception('Uservote is null.');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($userVote);
        $em->flush();
        $this->addFlash('success', 'Thanks for voting!');

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/new/", name="app_event_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     */
    public function createEvent(Request $request)
    {
        $club = $this->getUser()->getClub();
        $event = new Event();
        $form = $this->createForm(EventEditType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $this
                ->get('event_manager')
                ->fillEvent($event, $club)
            ;
            $event->setOwner($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Your event was created!');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('/event/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_event_edit")
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
        if ($this->getUser() != $event->getOwner()) {
            throw new NotFoundHttpException('You can\'t edit this page.');
        }

        $form = $this->createForm(EventEditType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $event->setVotingEnd($this->get('event_manager')->setVotingEndDate($event));
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event edited!');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('/event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_event_delete")
     * @Method({"GET"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return new JsonResponse($this->generateUrl('app_index'));
    }
}
