<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Tag;
use AppBundle\Form\DataTransformer\TagTransformer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

abstract class AbstractStoryAdmin extends Admin
{
    private $default_locale;

    private $locales;

    public function setParameters($default_locale, $locales)
    {
        $this->default_locale = $default_locale;
        $this->locales = $locales;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $namespaceParts = explode('\\', get_class($this->getSubject()));
        $mediaContext = strtolower(array_pop($namespaceParts));

        $formMapper
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
                        'context' => $mediaContext,
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add(
                $formMapper->create('tags', 'text', [
                    'empty_data' => $this->subject->getTags(),
                    'attr' => ['class' => 'posts-tags'],
                    'required' => false,
                ])
                    ->addModelTransformer(
                        new TagTransformer(
                            $this->default_locale,
                            $this->locales,
                            $this->modelManager->getEntityManager(new Tag())
                        )
                    )
            )
            ->add('galleryHasMedia', 'sonata_type_collection',
                [
                    'required' => false,
                    'label' => 'Gallery',
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
                    'targetEntity' => 'Application\Sonata\MediaBundle\Entity\GalleryHasMedia',
                    'admin_code' => 'sonata.media.admin.gallery_has_media',
                    'link_parameters' => [
                        'context'  => $mediaContext,
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
