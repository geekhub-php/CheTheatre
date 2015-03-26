<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PerformanceAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Performance';
    protected $baseRoutePattern = 'Performance';
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
            ->add('title', ['label' => 'label.label_title'])
            ->add('type', ['label' => 'label.label_type'])
            ->add('description', ['label' => 'label.label_description'])
            ->add('premiere', ['label' => 'label.label_premiere'])
            ->add('performanceEvents', ['label' => 'label.label_performanceEvents'])
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
            ->add('title', ['label' => 'label.label_title'])
            ->add('type', ['label' => 'label.label_type'])
            ->add('description', ['label' => 'label.label_description'])
            ->add('mainPicture', 'sonata_type_model_list',
                [
                    'required' => false,
                    'btn_list' => false,
                    'label' => 'label.label_mainPicture'
                ], [
                    'link_parameters' => [
                        'context'  => 'performance',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('sliderImage', 'sonata_type_model_list',
                [
                    'required' => false,
                    'btn_list' => false,
                    'label' => 'label.label_sliderImage'
                ], [
                    'link_parameters' => [
                        'context'  => 'slider',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('premiere', 'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                    'label' => 'label.label_premiere'
                ]
            )
            ->add('roles', 'sonata_type_collection',
                [
                    'by_reference' => false,
                    'label' => 'label.label_roles'

                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                ]
            )
            ->add('galleryHasMedia', 'sonata_type_collection',
                [
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
            ->add('mainPicture', 'string', [
                'template' => '::SonataAdmin/thumbnail.html.twig',
                'label' => 'label.label_mainPicture'
            ])
            ->addIdentifier('title', ['label' => 'label.label_title'])
            ->add('type', ['label' => 'label.label_type'])
            ->add('premiere', ['label' => 'label.label_premiere'])
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
            ->add('type', ['label' => 'label.label_type'])
        ;
    }
}
