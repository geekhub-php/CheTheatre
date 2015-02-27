<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Entity\Employee;

class EmployeeAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\Employee';
    protected $baseRoutePattern = 'Employee';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by'    => 'name',
    ];

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position')
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
        $formMapper
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('avatar', 'sonata_type_model_list', [
                'required' => false,
                'btn_list' => false,
            ], [
                'link_parameters' => [
                    'context'  => 'default',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add('dob', 'sonata_type_date_picker')
            ->add(
                'position',
                'choice',
                array('choices' => array(
                    'POSITION_ACTOR' => 'actor',
                    'POSITION_ACTRESS' => 'actress',
                    'POSITION_THEATRE_DIRECTOR' => 'theatre director',
                    'POSITION_ACTING_ARTISTIC_DIRECTOR' => 'acting artistic director',
                    'POSITION_PRODUCTION_DIRECTOR' => 'production director',
                    'POSITION_MAIN_ARTIST' => 'main artist',
                    'POSITION_COSTUMER' => 'costumer',
                    'POSITION_ART_DIRECTOR' => 'art director',
                    'POSITION_MAIN_CHOREOGPAPHER' => 'main choreographer',
                    'POSITION_HEAD_OF_THE_LITERARY_AND_DRAMATIC_PART' => 'head of the literary and dramatic part',
                    'POSITION_CONDUCTOR' => 'conductor',
                    'POSITION_ACCOMPANIST' => 'accompanist',
                )));
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position')
            ->add('roles')
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
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('dob')
            ->add('position')
            ->add('roles')
        ;
    }
}
