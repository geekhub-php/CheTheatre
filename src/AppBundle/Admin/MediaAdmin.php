<?php

namespace AppBundle\Admin;

use Sonata\MediaBundle\Admin\BaseMediaAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class MediaAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Media';
    protected $baseRoutePattern = 'Media';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by'    => 'name',
    ];

    /**
     * @param  \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('providerReference')
            ->add('enabled')
            ->add('context')
        ;

        $providers = array();

        $providerNames = (array) $this->pool->getProviderNamesByContext($this->getPersistentParameter('context', $this->pool->getDefaultContext()));
        foreach ($providerNames as $name) {
            $providers[$name] = $name;
        }

        $datagridMapper->add('providerName', 'doctrine_orm_choice', array(
            'field_options'=> array(
                'choices' => $providers,
                'required' => false,
                'multiple' => false,
                'expanded' => false,
            ),
            'field_type'=> 'choice',
        ));
    }
}