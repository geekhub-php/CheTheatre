<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PriceCategory;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PriceCategoryAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PriceCategory';
    protected $baseRoutePattern = 'PriceCategory';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'venue',
    ];

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('color', 'sonata_type_color_selector')
            ->add('venue')
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
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('venue')
        ;
    }

    public function toString($object)
    {
        return $object instanceof PriceCategory
            ? $object->getTitle()
            : 'PriceCategory'; // shown in the breadcrumb on the create views
    }
}
