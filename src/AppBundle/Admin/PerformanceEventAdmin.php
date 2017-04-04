<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PriceCategory;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PerformanceEventAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'dateTime',
    ];

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('getVenue')
            ->add('deletePriceCategories')
        ;
    }


    /**
     * @param FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('PerformanceEvents', ['class'=>'col-lg-12'])
            ->add('performance', 'sonata_type_model')
            ->add(
                'dateTime',
                'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                ]
            )
            ->add('venue')
            ->end()
            ->with('PriceCategory', ['class'=>'col-lg-12'])
            ->add('priceCategories', 'sonata_type_collection', [
                'by_reference' => true,
                'required' => false,
                'cascade_validation' => true,
                'type_options'       => [
                    'delete' => true,
                ],
                'label' => false,
            ], [
                'inline'            => 'table',
                'edit'            => 'inline',
                'sortable'        => 'position',
                'link_parameters'       => [
                    'performanceEvent_id' => $this->getSubject()->getId(),
                ],
            ])
            ->end()
            ->with('EnableSale', ['class'=>'col-lg-12'])
            ->add(
                'seriesDate',
                'sonata_type_datetime_picker',
                [
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => true,
                    'dp_use_seconds'        => false,
                    'format' => "dd/MM/yyyy HH:mm",
                    'required' => false,
                ]
            )
            ->add('seriesNumber', null, [
            'required' => false,
            ])
            ->add('enableSale', null, [
            'required' => false,
            ])
            ->end()
        ;
    }

    /**
     * @param ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('performance')
            ->add('dateTime')
            ->add('venue')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('performance')
            ->add('venue')
        ;
    }

    public function preUpdate($object)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        $categories = $em->getRepository('AppBundle:PriceCategory')->findBy(['performanceEvent' => $object]);
        $venue = $object->getVenue()->getTitle();

        /** @var PriceCategory $category*/

        foreach ($categories as $category) {
            self::getRows($venue, $category->getRows(), $category->getVenueSector(), $category->getPlaces());
        }
    }

    private function getSeat($venue, $row, $venueSector, $place = null)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        if ($place === null) {
            $seat = $em->getRepository('AppBundle:Seat')->findBy([
                'row' => $row,
                'venueSector' => $venueSector,
            ]);
            if (!$seat) {
                $this->getRequest()->getSession()->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. В залi $venue немає $row ряда в секторі $venueSector!"
                    );
                throw new ModelManagerException('Error!');
            }
        }
        if ($place !== null) {
            $seat = $em->getRepository('AppBundle:Seat')->findOneBy([
                'row' => $row,
                'place' => $place,
                'venueSector' => $venueSector,
            ]);
            if (!$seat) {
                $this->getRequest()->getSession()->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. В залi $venue немає $row - $place в секторі $venueSector!"
                    );
                throw new ModelManagerException('Error!');
            }
        }
    }

    private function getPlaces($venue, $row, $venueSector, $strPlaces)
    {
        if ($strPlaces === null) {
            self::getSeat($venue, $row, $venueSector);
            return;
        }
        $dataPlaces = explode(',', $strPlaces);
        foreach ($dataPlaces as $places) {
            if (substr_count($places, '-') === 1) {
                list($begin, $end) = explode('-', $places);
                for ($place = $begin; $place <= $end; $place++) {
                    self::getSeat($venue, $row, $venueSector, $place);
                }
            }
            if (substr_count($places, '-') === 0) {
                self::getSeat($venue, $row, $venueSector, $places);
            }
        }
    }

    private function getRows($venue, $strRows, $venueSector, $strPlaces)
    {
        $dataRows = explode(',', $strRows);
        foreach ($dataRows as $rows) {
            if (substr_count($rows, '-') === 1) {
                list($begin, $end) = explode('-', $rows);
                for ($row = $begin; $row <= $end; $row++) {
                    self::getPlaces($venue, $row, $venueSector, $strPlaces);
                }
            }
            if (substr_count($rows, '-') === 0) {
                self::getPlaces($venue, $rows, $venueSector, $strPlaces);
            }
        }
    }
}
