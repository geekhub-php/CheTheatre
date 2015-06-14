<?php

namespace AppBundle\Admin;

use AppBundle\Entity\History;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class HistoryAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\History';
    protected $baseRoutePattern = 'History';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'name',
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
            ->add('type', 'choice', [
                'choices'  => History::getTypes()
            ])
            ->add('dateTime', 'datetime',
                [
                    'label' => 'History_Date',
                    'widget' => 'single_text',
                    'format' => 'yyyy'
                ]
            )
            ->add('text', 'textarea',
                [
                    'attr' => [
                            'class' => 'wysihtml5',
                            'style' => 'height:300px',
                    ],
                ]
            )
            ->add('mainPicture', 'sonata_type_model_list',
                [
                    'required' => false,
                    'btn_list' => false,
                ], [
                    'link_parameters' => [
                        'context' => 'history',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('galleryHasMedia', 'sonata_type_collection',
                [
                    'required' => false,
                    'label' => 'Gallery'
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                    'targetEntity' => 'Application\Sonata\MediaBundle\Entity\GalleryHasMedia',
                    'admin_code' => 'sonata.media.admin.gallery_has_media',
                    'link_parameters' => [
                        'context'  => 'history',
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
            ->add('mainPicture', 'string', ['template' => '::SonataAdmin/thumbnail.html.twig'])
            ->add('year', null, ['label' => 'History_Date'])
            ->addIdentifier('title')
            ->add('type')
            ->add('_action', 'actions',
                [
                    'actions' => [
                        'edit' => [],
                        'delete' => [],
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
            ->add('title')
            ->add('dateTime');
    }
}
