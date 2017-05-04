<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Client;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ClientAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Client';
    protected $baseRoutePattern = 'Client';
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
            ->with('Swindler', ['class' => 'col-lg-12'])
            ->add('ip', 'text', ['attr' => ['readonly' => true]])
             ->add('countAttempts', 'text', ['attr' => ['readonly' => true]])
            ->add('banned', 'text', ['attr' => ['readonly' => true]])
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('ip')
            ->add('countAttempts')
            ->add('banned')
            ->add('_action', 'actions', [
                'actions' => [
                    'delete' => [],
                    'lock' => array(
                        'template' => 'AppBundle:SwindlerLocked:swindlerLock.html.twig',
                    ),
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
            ->add('ip')
        ;
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('lock')
            ->add('unlock')
        ;
    }
}
