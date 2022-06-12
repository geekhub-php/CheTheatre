<?php

namespace App\Admin;

use App\Entity\History;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HistoryAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'App\Entity\History';
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
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('title')
            ->add('type', ChoiceType::class, [
                'choices'  => History::getTypes()
            ])
            ->add('dateTime', DateTimePickerType::class,
                [
                    'label' => 'History_Date',
                    'widget' => 'single_text',
                    'format' => 'yyyy'
                ]
            )
            ->add('text', CKEditorType::class,
                [
                    'attr' => [
                            'class' => 'wysihtml5',
                            'style' => 'height:300px',
                    ],
                ]
            )
            ->add('mainPicture', ModelListType::class,
                [
                    'required' => true,
                    'btn_list' => false,
                ], [
                    'link_parameters' => [
                        'context' => 'history',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add('galleryHasMedia', CollectionType::class,
                [
                    'required' => false,
                    'label' => 'Gallery'
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                    'targetEntity' => 'App\Entity\GalleryHasMedia',
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
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('mainPicture', 'string', ['template' => 'bundles/SonataAdmin/thumbnail.html.twig'])
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
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('title')
            ->add('dateTime');
    }
}
