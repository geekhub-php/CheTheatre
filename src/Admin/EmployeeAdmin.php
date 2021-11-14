<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Entity\Employee;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EmployeeAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'App\Entity\Employee';
    protected $baseRoutePattern = 'Employee';
    protected $perPageOptions = [16, 32, 64, 128, 192, 'All'];
    protected $maxPerPage = 'All';
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by'    => 'orderPosition',
        '_per_page' => 'All',
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
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position')
            ->add('employeeGroup')
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
            ->add('lastName')
            ->add('avatar', ModelListType::class, [
                'required' => false,
                'btn_list' => false,
            ], [
                'link_parameters' => [
                    'context'  => 'employee',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
            ->add('dob', DateTimePickerType::class,
                [
                    'dp_side_by_side'       => false,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'dp_use_minutes'        => false,
                    'format' => "dd/MM/yyyy",
                ]
            )
            ->add('position', ChoiceType::class, [
                'label' => 'employee.position',
                'choices' => employee::getPositions(),
                'translation_domain' => 'messages',
                ]
            )
            ->add('employeeGroup', null, ['required' => true])
            ->add('biography', CKEditorType::class,
                [
//                    'format' => 'richhtml',
//                    'ckeditor_context' => 'default',
                ]
            )
            ->add('galleryHasMedia', CollectionType::class, [
                'required' => false,
                'label' => 'Gallery',
                ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
                'targetEntity' => 'App\Entity\GalleryHasMedia',
                'admin_code' => 'sonata.media.admin.gallery_has_media',
                'link_parameters' => [
                    'context'  => 'employee',
                    'provider' => 'sonata.media.provider.image',
                ],
            ])
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
            ->add('avatar', 'string', ['template' => 'bundles/SonataAdmin/thumbnail.html.twig'])
            ->addIdentifier('firstName')
            ->add('lastName')
            ->add('dob', 'date')
            ->add('position', 'choice', [
                    'choices' => employee::getPositions(),
                    'catalogue' => 'messages',
                ]
            )
            ->add('roles')
            ->add('employeeGroup')
            ->add('_action', null, [
                'actions' => [
                    'move' => [
                        'template' => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig'
                    ],
                ],
            ])
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
            ->add('employeeGroup')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }
}
