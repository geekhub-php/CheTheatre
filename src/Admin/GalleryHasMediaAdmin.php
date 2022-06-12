<?php

namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\MediaBundle\Admin\BaseMediaAdmin as BaseGalleryHasMediaAdmin;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GalleryHasMediaAdmin extends BaseGalleryHasMediaAdmin
{
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        parent::configureFormFields($formMapper);

        $mediaField = $field = $formMapper->get('media');
        $options    = $mediaField->getFormConfig()->getOptions();
        $options['btn_delete'] = false;

        $formMapper
            ->remove('enabled')
            ->remove('position')
            ->add('title', null, ['label' => 'Title', 'attr' => ['style' => 'height: 150px']])
            ->add('description', null, ['attr' => ['style' => 'height: 150px; width: 400px']])
            ->add('media', ModelListType::class, $options)
        ;
    }
}
