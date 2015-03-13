<?php

namespace AppBundle\Admin;

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
            ->add('media', $mediaField->getType()->getName(), $options)
        ;
    }
}
