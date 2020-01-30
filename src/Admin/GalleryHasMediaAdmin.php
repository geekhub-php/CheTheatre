<?php

namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Admin\GalleryHasMediaAdmin as BaseGalleryHasMediaAdmin;

class GalleryHasMediaAdmin extends BaseGalleryHasMediaAdmin
{
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $mediaField = $field = $formMapper->get('media');
        $options    = $mediaField->getFormConfig()->getOptions();
        $options['btn_delete'] = false;

        $formMapper
            ->remove('enabled')
            ->remove('position')
            ->add('title', 'textarea', ['label' => 'Title', 'attr' => ['style' => 'height: 150px']])
            ->add('description', 'textarea', ['attr' => ['style' => 'height: 150px; width: 400px']])
            ->add('media', $mediaField->getType()->getName(), $options)
        ;
    }
}
