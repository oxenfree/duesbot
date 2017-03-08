<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/4/17
 * Time: 10:04 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Entity\Due;
use AppBundle\Entity\User;
use AppBundle\Form\DueType;
use Stripe\Error\Card as CardError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DueController
 * @package AppBundle\Controller
 *
 * @Route("/due")
 */
class DueController extends Controller
{
    /**
     * @Route("/new", name="app_due_create")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $club = $this->getUser()->getClub();
        $user = $this->getUser();

        $due = $this
            ->getDoctrine()
            ->getRepository(Due::class)
            ->findOneBy(['user' => $user, 'club' => $club])
        ;

        if (!isset($due))   {
            $due = new Due();
        } elseif ($due->isCheckedOut())  {
            throw new NotFoundHttpException('You\'ve already paid dues for this club.');
        }

        $form = $this->createForm(DueType::class, $due);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())   {
            $due->setUser($user)->setClub($club);
            $em = $this->getDoctrine()->getManager();
            $em->persist($due);
            $em->flush();

            return $this->redirectToRoute('app_checkout');
        }

        return $this->render('/due/new.html.twig',[
            'due' => $due,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/checkout", name="app_checkout")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function checkoutAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Club $club */
        $club = $user->getClub();
        /** @var Due $due */
        $due = $this
            ->getDoctrine()
            ->getRepository(Due::class)
            ->findOneBy(['user' => $user, 'club' => $club])
        ;

        if (!$due || $due->isCheckedOut())   {
            throw new NotFoundHttpException('Dues not found --try to go back through the homepage.');
        }

        $error = false;
        if ($request->isMethod('POST')) {
            $token = $request->get('stripeToken');
            $stripeManager = $this->get('stripe_manager');

            try {
                $customer = (!$user->getStripeCustomerId()) ?
                    $stripeManager->createCustomer($user, $token) :
                    $stripeManager->updateCustomer($user, $token);

                $amountToStripe = $due->getAmountPerMonth() * 100;

                $planId = sprintf('%s_monthly_%s', $club->getName(), $amountToStripe);
                $plan = $stripeManager->getPlan($planId);

                if (!$plan) {
                    $stripeManager->createPlan($club, $planId, $amountToStripe);
                }

                $due->setSubscriptionId($stripeManager->createSubscription($customer, $planId));

            } catch (CardError $e)    {
                $error = sprintf('There was a charge error. %s Please try again.', $e->getMessage());
            }

            if (!$error)    {
                $this->addFlash('success', "Dues order complete!");
                $due->setCheckedOut(true);
                $this->getDoctrine()->getManager()->flush();
                $this->get('email_manager')->sendConfirmationEmail($user);

                return $this->redirectToRoute('app_index');
            }
        }

        return $this->render('/due/checkout.html.twig', [
            'due' => $due,
            'club' => $club,
            'user' => $user,
            'stripe_public_key' => $this->container->getParameter('stripe_public_key'),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/cancel/{id}", name="app_due_cancel")
     * @Method({"DELETE"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function cancelAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Due $due */
        $due = $em->getRepository(Due::class)->find($id);
        $stripeManager = $this->get('stripe_manager');
        $stripeManager->cancelDues($due);
        $em->remove($due);
        $em->flush();

        return new JsonResponse($this->generateUrl('app_index'));
    }
}
