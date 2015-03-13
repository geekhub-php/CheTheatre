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
        $link_parameters = array();

        if ($this->hasParentFieldDescription()) {
            $link_parameters = $this->getParentFieldDescription()->getOption('link_parameters', array());
        }

        if ($this->hasRequest()) {
            $context = $this->getRequest()->get('context', null);

            if (null !== $context) {
                $link_parameters['context'] = $context;
            }
        }

        $formMapper
            ->add('media', 'sonata_type_model_list', array(
                'required' => false,
                'btn_delete' => false
            ),
                array(
                'link_parameters' => $link_parameters
                )
            )

        ;
    }
}
