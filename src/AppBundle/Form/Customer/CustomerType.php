<?php

namespace AppBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apiKeyToken')
            ->add('firstName')
            ->add('lastName')
            ->add('email', 'email')
            ->add('socialNetwork')
            ->add('socialToken')
             ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DTOCustomer',
            'attr'              => array('novalidate' => 'novalidate')
        ));
    }


    public function getBlockPrefix()
    {
        return 'DTOCustomer';
    }

}