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

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('shortDescription')
            ->add('text', 'textarea', array('attr' => array('class' => 'wysihtml5','style' => 'height:300px')))
            ->add('mainPicture', 'sonata_type_model_list', [
                'required' => false,
                'btn_list' => false,
            ], [
                'link_parameters' => [
                    'context' => 'default',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add(
                $formMapper->create('tags', 'text', ['attr' => ['class' => 'posts-tags']])
                    ->addModelTransformer(new TagTransformer($this->modelManager->getEntityManager(new Tag())))
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
            ->add('mainPicture', 'sonata_type_model_list', [
                'required' => false,
                'btn_list' => false,
            ], [
                'link_parameters' => [
                    'context' => 'default',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->addIdentifier('title')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))

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
            ->add('tags', null, array(), null, array('expanded' => true, 'multiple' => true));
    }
}
