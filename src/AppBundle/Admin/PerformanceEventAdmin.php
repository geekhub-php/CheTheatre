<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PerformanceEventAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
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
            ->add('performance', ['label' => 'label.label_performance'])
            ->add('dateTime', ['label' => 'label.label_datetime'])
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
            ->add('performance', 'sonata_type_model', ['label' => 'label.label_performance'])
            ->add('dateTime', 'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                    'label' => 'label.label_datetime',
                ]
            )
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('performance', ['label' => 'label.label_performance'])
            ->add('dateTime', ['label' => 'label.label_datetime'])
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
            ->add('performance', ['label' => 'label.label_performance'])
            ->add('dateTime', ['label' => 'label.label_datetime'])
        ;
    }
}
