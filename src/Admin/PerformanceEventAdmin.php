<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use App\Entity\PerformanceEvent;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PerformanceEventAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'App\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'dateTime',
    ];

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('performance', ModelType::class)
            ->add('dateTime', DateTimePickerType::class,
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                ]
            )
            ->add('venue', ChoiceType::class, [
                'choices'     => PerformanceEvent::$venues,
                'placeholder' => 'choose_an_option',
            ])
            ->add('buyTicketLink')
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
            ->add('performance')
            ->add('dateTime')
            ->add('venue', null, ['template' => "App:SonataAdmin:list_field.html.twig"])
            ->add('buyTicketLink', 'string', ['template' => 'bundles/SonataAdmin/qr_list.html.twig'])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
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
            ->add('performance')
            ->add('venue', 'doctrine_orm_choice', [],
                ChoiceType::class,
                [
                    'choices' => PerformanceEvent::$venues,
                    'expanded' => true,
                    'multiple' => true
                ]
            )
        ;
    }
}
