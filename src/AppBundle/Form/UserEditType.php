<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/26/17
 * Time: 8:19 PM
 */

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class, [
                'attr' => [
                    'class' => 'materialize-textarea',
                ],
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'materialize-textarea',
                ],
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => function (Club $club) {
                    return $club->getName();
                }
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Member' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
            ]);

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
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
