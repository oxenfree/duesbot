<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/4/17
 * Time: 5:40 PM
 */

namespace AppBundle\Services;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Balance;
use Stripe\Plan;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Error\InvalidRequest;

class StripeManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * StripeManager constructor.
     *
     * @param $secretKey
     * @param EntityManagerInterface $entityManager
     */
    public function __construct($secretKey, EntityManagerInterface $entityManager)
    {
        Stripe::setApiKey($secretKey);
        $this->em = $entityManager;
    }

    /**
     * @param User $user
     * @param $paymentToken
     *
     * @return Customer
     */
    public function createCustomer(User $user, $paymentToken)
    {
        $customer = Customer::create(array(
            "email" => $user->getEmail(),
            "source" => $paymentToken,
        ));

        $user->setStripeCustomerId($customer->id);
        $this->em->persist($user);
        $this->em->flush();

        return $customer;
    }

    /**
     * @param User $user
     * @param $paymentToken
     *
     * @return Customer
     */
    public function updateCustomer(User $user, $paymentToken)
    {
        $customer = Customer::retrieve($user->getStripeCustomerId());
        $customer->source = $paymentToken;
        $customer->save();

        return $customer;
    }

    /**
     * @param $amount
     * @param User $user
     *
     * @return Charge
     */
    public function createCharge($amount, User $user)
    {
       return Charge::create([
            "amount" => $amount,
            "currency" => "usd",
            "customer" => $user->getStripeCustomerId(),
            "description" => sprintf(
                'Dues paid to %s by %s.',
                $user->getClub()->getName(),
                $user->getEmail()
            )
       ]);

    }

    public function getPlan($id)
    {
        try {
            $plan = Plan::retrieve($id);
        } catch (InvalidRequest $e) {
            $plan = false;
        }

        return $plan;
    }

    public function createPlan(Club $club, $planId, $amount)
    {
        $plan = Plan::create([
            "amount" => $amount,
            "interval" => "month",
            "name" => sprintf('%s - %d - monthly', $club->getName(), $amount),
            "currency" => "usd",
            "id" => $planId,
        ]);

        return $plan;
    }

    public function createSubscription(Customer $customer, $planId)
    {
        Subscription::create(array(
            "customer" => $customer->id,
            "plan" => $planId,
        ));
    }

    public function getBalance()
    {
        return Balance::retrieve();
    }
}
