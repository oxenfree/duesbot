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
use AppBundle\Entity\UserVote;
use AppBundle\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $userVotes = $event->getUserVotes();
        $voteYes = null;
        $voteNo = null;

        foreach ($userVotes as $key => $userVote) {
            if ($this->getUser()->getUsername() == $userVote->getUser()->getUsername()) {
                $voteYes = $userVote->getVoteYes() ? true : false;
                $voteNo = !$voteYes;
            }
        }

        $status = $event->getStatus();
        if ($status->getValue() == EventStatus::VOTING_CLOSED)  {
            $voteYes = true;
            $voteNo = true;
        }

        $voteTotal = $event->getVoteTotal();

        return $this->render('/event/show.html.twig', [
            'event' => $event,
            'userVotes' => $userVotes,
            'voteTotal' => $voteTotal,
            'voteYes' => $voteYes,
            'voteNo' => $voteNo,
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
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        $user = $this->getUser();
        $userVote = new UserVote();
        $userVote->setUser($user)
            ->setVoteYes(true);
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($userVote);
        $event->addUserVote($userVote);
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
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        $user = $this->getUser();
        $userVote = new UserVote();
        $userVote->setUser($user)
            ->setVoteYes(false);
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($userVote);
        $event->addUserVote($userVote);
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
            $status = (new EventStatus())
                ->setValue(EventStatus::VOTING_OPEN);
            $event->setVotingStart(new \DateTime('now'));
            $event->setClub($club);
            $event->setStatus($status);
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
}
