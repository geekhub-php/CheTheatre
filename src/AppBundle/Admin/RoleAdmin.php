<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Entity\Role;

class RoleAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Role';
    protected $baseRoutePattern = 'Role';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by'    => 'name',
    ];

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('description')
            ->add('performance')
            ->add('employee')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', ['label' => 'label.label_title'])
            ->add('description', ['label' => 'label.label_description'])
            ->add('employee', 'sonata_type_model', ['label' => 'label.label_employee', 'required' => false]);
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', ['label' => 'label.label_title'])
            ->add('description', ['label' => 'label.label_description'])
            ->add('performance', ['label' => 'label.label_performance'])
            ->add('employee', ['label' => 'label.label_employee'])
            ->add('_action', 'actions',
                [
                    'actions' => [
                        'show' => ['label' => 'label.label_show'],
                        'edit' => ['label' => 'label.label_edit'],
                        'delete' => ['label' => 'label.label_delete'],
                    ],
                ]
            )
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', ['label' => 'label.label_title'])
            ->add('description', ['label' => 'label.label_description'])
            ->add('performance', ['label' => 'label.label_performance'])
            ->add('employee', ['label' => 'label.label_employee'])
        ;
    }
}
