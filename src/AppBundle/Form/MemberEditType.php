<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/22/17
 * Time: 5:56 AM
 */

namespace AppBundle\Form;


use AppBundle\Entity\Member;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MemberEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('nickName', TextType::class, [
                'required' => false,
            ])
            ->add('phone', TextType::class)
            ->add('email', TextType::class)
        ;

        /*
         * This event listener checks the form before it is submitted to see if the User is new.
         * If so it makes the password form required and NotBlank.
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $member = $event->getData();
            $form = $event->getForm();
            $formParams = [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ];

            if ((!$member || null === $member->getId())) {
                $formParams['constraints'] = new NotBlank();
            } else {
                $formParams['required'] = false;
            }

            $form->add('plainPassword', RepeatedType::class, $formParams);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Member::class]);
    }
}