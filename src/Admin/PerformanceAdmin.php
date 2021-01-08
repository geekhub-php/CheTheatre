<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DateTimePickerType;

class PerformanceAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'App\Entity\Performance';
    protected $baseRoutePattern = 'Performance';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'premiere',
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
            ->add('type')
            ->add('description')
            ->add('premiere')
            ->add('performanceEvents')
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
        $em = $this->modelManager->getEntityManager('App\Entity\History');

        $query = $em->createQueryBuilder('h')
            ->select('h')
            ->from('App:History', 'h')
            ->where('h.type = :type')
            ->orderBy('h.createdAt', 'ASC')
            ->setParameter('type', 'festival');

        $formMapper
            ->add('title')
            ->add('type')
            ->add('festival', ModelType::class, [
                'required' => false,
                'query' => $query,
            ])
            ->add('description', CKEditorType::class, ['attr' => ['class' => 'wysihtml5', 'style' => 'height: 200px']])
            ->add('mainPicture', ModelListType::class,
                [
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'performance',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('sliderImage', ModelListType::class,
                [
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'context'  => 'slider',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('premiere', DateTimePickerType::class,
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                ]
            )
            ->add('seasons')
            ->add('audience', null, ['required' => true])
            ->add('roles', CollectionType::class,
                [
                    'required' => false,
                    'by_reference' => false,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                ]
            )
            ->add('galleryHasMedia', CollectionType::class,
                [
                    'required' => false,
                    'label' => 'Gallery',
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                    'targetEntity' => 'App\Entity\GalleryHasMedia',
                    'admin_code' => 'sonata.media.admin.gallery_has_media',
                    'link_parameters' => [
                        'context'  => 'performance',
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
            ->add('mainPicture', 'string', ['template' => 'bundles/SonataAdmin/thumbnail.html.twig'])
            ->addIdentifier('title')
            ->add('type')
            ->add('seasons')
            ->add('premiere')
            ->add('festival')
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
            ->add('type')
            ->add('festival')
            ->add('seasons')
        ;
    }
}
