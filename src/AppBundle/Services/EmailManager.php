<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 2/6/17
 * Time: 7:28 PM
 */

namespace AppBundle\Services;

use AppBundle\Entity\Due;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Twig_Environment;

class EmailManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $adminEmail;

    /**
     * EmailManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param Swift_Mailer           $mailer
     * @param Twig_Environment       $twig
     * @param string                 $adminEmail
     */
    public function __construct(EntityManagerInterface $em, Swift_Mailer $mailer, Twig_Environment $twig, $adminEmail)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->adminEmail = $adminEmail;
    }

    public function sendConfirmationEmail(User $user)
    {
        $subject = "Thanks for signing up with Duesbot";
        $from = $this->adminEmail;
        $emailBody = $this->renderConfirmationTemplate($user);

        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setBcc($from)
                ->addTo($user->getEmail())
                ->setBody($emailBody, 'text/html')
            ;
            $this->mailer->send($message);
        } catch (Exception $e) {
           $message = \Swift_Message::newInstance()
               ->setTo($from)
               ->setFrom($from)
               ->setSubject('Problem with sending email to '.$user->getEmail())
               ->setBody($e->getMessage())
           ;
           $this->mailer->send($message);
        }
    }

    private function renderConfirmationTemplate(User $user)
    {
        $due = $this->em->getRepository(Due::class)->findOneBy(['user' => $user]);
        return $this->twig->render('email/confirmation.html.twig', [
            'user' => $user,
            'due' => $due,
        ]);
    }
}