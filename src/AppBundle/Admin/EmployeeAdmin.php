<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Entity\Employee;

class EmployeeAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Employee';
    protected $baseRoutePattern = 'Employee';
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
            ->add('firstName', ['label' => 'label.label_firstName'])
            ->add('middleName', ['label' => 'label.label_middleName'])
            ->add('lastName', ['label' => 'label.label_lastName'])
            ->add('dob', 'date', ['label' => 'label.label_dob'])
            ->add('position', ['label' => 'label.label_position'])
            ->add('roles', ['label' => 'label.label_roles'])
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
            ->add('firstName', ['label' => 'label.label_firstName'])
            ->add('middleName', ['label' => 'label.label_middleName'])
            ->add('lastName', ['label' => 'label.label_lastName'])
            ->add('avatar', 'sonata_type_model_list', [
                'required' => false,
                'btn_list' => false,
                'label' => 'label.label_avatar'
            ], [
                'link_parameters' => [
                    'context'  => 'employee',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add('dob', 'sonata_type_date_picker', ['label' => 'label.label_dob'])
            ->add('position', 'choice', [
                    'choices' => employee::getPositions(),
                    'translation_domain' => 'messages',
                    'label' => 'label.label_position',
                ]
            )
            ->add('galleryHasMedia', 'sonata_type_collection', [
                'required' => false,
                'label' => 'label.label_gallery',
                ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
                'targetEntity' => 'Application\Sonata\MediaBundle\Entity\GalleryHasMedia',
                'admin_code' => 'sonata.media.admin.gallery_has_media',
                'link_parameters' => [
                    'context'  => 'employee',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
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
            ->add('avatar', 'string', ['template' => '::SonataAdmin/thumbnail.html.twig'])
            ->addIdentifier('firstName', ['label' => 'label.label_firstName'])
            ->add('middleName', ['label' => 'label.label_middleName'])
            ->add('lastName', ['label' => 'label.label_lastName'])
            ->add('dob', 'date', ['label' => 'label.label_dob'])
            ->add('position', ['label' => 'label.label_position'])
            ->add('roles', ['label' => 'label.label_roles'])
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
            ->add('firstName', ['label' => 'label.label_firstName'])
            ->add('middleName', ['label' => 'label.label_middleName'])
            ->add('lastName', ['label' => 'label.label_lastName'])
            ->add('dob', ['label' => 'label.label_dob'])
            ->add('position', ['label' => 'label.label_position'])
            ->add('roles', ['label' => 'label.label_roles'])
        ;
    }
}
