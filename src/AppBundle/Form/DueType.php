<?php
/**
 * Created by PhpStorm.
 * User: ollie
 * Date: 1/22/17
 * Time: 5:56 AM
 */

namespace AppBundle\Form;

use AppBundle\Entity\Due;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amountPerMonth', NumberType::class, [
                'label' => '$ Amount per Month'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Due::class]);
    }
}