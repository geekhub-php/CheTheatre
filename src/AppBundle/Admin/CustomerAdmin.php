<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Customer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CustomerAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Customer';
    protected $baseRoutePattern = 'Customer';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Customer', ['class' => 'col-lg-12'])
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('facebookId')
            //->add('apiKey')
            ->add('apiKey', 'text', ['attr' => ['readonly' => true]])
            ->add('orders')
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('apiKey')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
        ;
    }

    public function toString($object)
    {
        return $object instanceof Customer
            ? $object->getEmail()
            : 'Customer'; // shown in the breadcrumb on the create views
    }
}
