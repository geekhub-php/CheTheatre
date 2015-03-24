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
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position')
            ->add('roles')
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
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('avatar', 'sonata_type_model_list', [
                'required' => false,
                'btn_list' => false,
            ], [
                'link_parameters' => [
                    'context'  => 'employee',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add('dob', 'sonata_type_date_picker')
            ->add('position', 'choice', [
                'choices' => employee::getPositions(),
                'translation_domain' => 'messages',
                ]
            )
            ->add('biography', 'textarea',
                [
                    'attr' => [
                        'class' => 'wysihtml5',
                        'style' => 'height:200px',
                    ],
                ]
            )
            ->add('galleryHasMedia', 'sonata_type_collection', [
                'required' => false,
                'label' => 'Gallery',
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
            ->addIdentifier('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position')
            ->add('roles')
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
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob')
            ->add('position')
            ->add('roles')
        ;
    }
}
