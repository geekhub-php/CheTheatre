<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Tag;
use AppBundle\Form\DataTransformer\TagTransformer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Post';
    protected $baseRoutePattern = 'Post';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'name',
    ];

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Posts')
            ->add('title')
            ->add('shortDescription')
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
                        'context' => 'default',
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->end()
            ->with('Tags', ['description' => '* Тег "Історія" використовуйте виключно для тих статей які повинні відображатись в розділі "Історія Театру"'])
            ->add(
                $formMapper->create('tags', 'text', ['empty_data' => $this->subject->getTags(), 'attr' => ['class' => 'posts-tags']])
                    ->addModelTransformer(
                        new TagTransformer(
                            $this->container->getParameter('sonata_translation.default_locale'),
                            $this->container->getParameter('sonata_translation.locales'),
                            $this->modelManager->getEntityManager(new Tag())
                        )
                    )
            )
            ->end()
            ->with('Gallery')
                ->add('galleryHasMedia', 'sonata_type_collection',
                    [
                        'required' => false,
                        'label' => false,
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
            ->add('mainPicture', 'string', ['template' => '::SonataAdmin/thumbnail.html.twig'])
            ->addIdentifier('title')
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
            ->add('tags', null, [], null, ['expanded' => true, 'multiple' => true]);
    }
}
