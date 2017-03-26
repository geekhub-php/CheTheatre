<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Type;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('firstName')
            ->add('lastName')
            ->add('apiKey')
            ->add('facebookId')
            ->add('socialNetwork', null, [
                'mapped' => false,
                'constraints' => [
                    new Type(['type' => 'string']),
                    new Choice(['choices' => ['facebook']])
                ]
            ])
            ->add('socialToken', null, ['mapped' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => Customer::class,
            'attr'              => array('novalidate' => 'novalidate'),
            'csrf_protection'   => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }
}
