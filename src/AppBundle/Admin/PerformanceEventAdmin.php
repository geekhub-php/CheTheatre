<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PerformanceEventAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'dateTime',
    ];

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('getVenue')
            ->add('deletePriceCategories')
        ;
    }


    /**
     * @param FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('PerformanceEvents', ['class'=>'col-lg-12'])
            ->add('performance', 'sonata_type_model')
            ->add(
                'dateTime',
                'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                ]
            )
            ->add('venue')
            ->end()
            ->with('PriceCategory', ['class'=>'col-lg-12'])
            ->add('priceCategories', 'sonata_type_collection', [
                'by_reference' => true,
                'required' => false,
                'cascade_validation' => true,
                'type_options'       => [
                    'delete' => true,
                ],
                'label' => false,
            ], [
                'inline'            => 'table',
                'edit'            => 'inline',
                'sortable'        => 'position',
                'link_parameters'       => [
                    'performanceEvent_id' => $this->getSubject()->getId(),
                ],
            ])
            ->end()
            ->with('EnableSale', ['class'=>'col-lg-12'])
            ->add(
                'seriesDate',
                'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => true,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                    'required' => false,
                ]
            )
            ->add('seriesNumber', null, [
            'required' => false,
            ])
            ->add('enableSale', null, [
            'required' => false,
            ])
            ->end()
        ;
    }

    /**
     * @param ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('performance')
            ->add('dateTime')
            ->add('venue')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('performance')
            ->add('venue')
        ;
    }
}
