<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;

class VenueAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Venue';
    protected $baseRoutePattern = 'Venue';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'id',
    ];

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Venue', ['class'=>'col-lg-12'])
            ->add('title')
            ->add('address')
            ->add('hallTemplate')
            ->end()
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
            ->add('title')
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
            ->add('title')
        ;
    }

    /**
     * @param mixed $object
     * @return bool
     * @throws ModelManagerException
     */
    public function preRemove($object)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        if (count($object->getPerformanceEvents()) != 0) {
            $message = sprintf('An Error has occurred during deletion of item "%s".', $object->getTitle());
            $em->detach($object);
            throw new ModelManagerException($message, 200);
        }
        return false;
    }
}
