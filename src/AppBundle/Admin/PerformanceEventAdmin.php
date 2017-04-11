<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\RowsForSale;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Venue;
use AppBundle\Entity\VenueSector;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PerformanceEventAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'dateTime',
    ];
    protected $seatPrice = [];

    /**
     * @var EntityManager
     */
    protected $em;

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
        $queryRowsForSale = $this->getEm()->getRepository('AppBundle:RowsForSale')
            ->findVenueSectorsByPerformanceEventQueryBuilder($this->getSubject());

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
                'inline'  => 'table',
                'edit' => 'inline',
                'sortable' => 'position',
                'link_parameters'       => [
                    'performanceEvent_id' => $this->getSubject()->getId(),
                ],
            ])
            ->end()
            ->with('EnableSale', ['class'=>'col-lg-12'])
            ->add('seriesDate', 'sonata_type_datetime_picker', [
                'dp_side_by_side'       => true,
                'dp_use_current'        => true,
                'dp_use_seconds'        => false,
                'format' => "dd/MM/yyyy HH:mm",
                'required' => false,
            ])
            ->add('rowsForSale', 'sonata_type_model', [
                'class' => RowsForSale::class,
                'required' => false,
                'multiple' => true,
                'query' => $queryRowsForSale,
            ])
        ;
        if ($this->getSubject()->isEnableSale() !== true) {
            $formMapper
                ->add('seriesNumber', null, [
                    'required' => false,
                ])
                ->add('sale', 'checkbox', [
                    'required' => false,
                    'label' => 'Enable Sale',
                ])
                ->end()
            ;
        }
        if ($this->getSubject()->isEnableSale() === true) {
            $formMapper
                ->add('seriesNumber', null, [
                    'required' => false,
                    'attr' => ['class' => 'hidden'],
                    'label' => false,
                ])
                ->add('enableSale', TextType::class, [
                    'required' => false,
                    'attr' => ['class' => 'hidden'],
                    'label' => false,
                ])
                ->end()
            ;
        }
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
            ]);
    }

    public function preUpdate($object)
    {
        $this->seatPrice = [];
        if (!self::inspectPriceCategories($object)) {
            return null;
        }
        if (!self::inspectSeatWithoutPrice($object->getVenue())) {
            return null;
        }
        if (!self::inspectSeriesNumber($object)) {
            return null;
        }
        if ($object->isEnableSale() === null) {
            $object->setEnableSale(false);
            $this->getEm()->persist($object);
        }
        if (($object->isEnableSale() === false) && ($object->isSale() === true)) {
            $object->setEnableSale(true);
            $this->getEm()->persist($object);
        }
        return true;
    }

    public function postUpdate($object)
    {
        $this->seatPrice = [];
        if (!self::inspectPriceCategories($object)) {
            return null;
        }
        if (!self::inspectSeatWithoutPrice($object->getVenue())) {
            return null;
        }
        if (!self::inspectSeriesNumber($object)) {
            return null;
        }
        return true;
    }

    public function getEm()
    {
        if (!$this->em) {
            $this->em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        }
        return $this->em;
    }

    /**
     * Inspect PriceCategory. Search errors
     *
     * @param PerformanceEvent $performanceEvent
     * @return bool
     */
    public function inspectPriceCategories(PerformanceEvent $performanceEvent)
    {
        $categories = $this->getEm()->getRepository('AppBundle:PriceCategory')->findBy(['performanceEvent' => $performanceEvent]);
        $venue = $performanceEvent->getVenue();
        /** @var PriceCategory $category*/
        foreach ($categories as $category) {
            self::getRows($venue, $category->getRows(), $category->getVenueSector(), $category->getPlaces());
        }
        if (!$categories) {
            return false;
        }
        return true;
    }

    /**
     * Parse string rows in PriceCategory
     *
     * @param Venue $venue
     * @param $strRows
     * @param VenueSector $venueSector
     * @param $strPlaces
     * @return bool|null
     */
    public function getRows(Venue $venue, $strRows, VenueSector $venueSector, $strPlaces = null)
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
        return true;
    }

    /**
     * Parse string places in PriceCategory
     *
     * @param Venue $venue
     * @param $row
     * @param VenueSector $venueSector
     * @param $strPlaces
     */
    public function getPlaces(Venue $venue, $row, VenueSector $venueSector, $strPlaces = null)
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

    /**
     * Research existing Seat with row-place - $row-$place
     *
     * @param Venue $venue
     * @param $row
     * @param VenueSector $venueSector
     * @param null $place
     * @throws ModelManagerException
     */
    public function getSeat(Venue $venue, $row, VenueSector $venueSector, $place = null)
    {
        if ($place === null) {
            $seat = $this->getEm()->getRepository('AppBundle:Seat')->findBy([
                'row' => $row,
                'venueSector' => $venueSector,
            ]);
            if (!$seat) {
                $this
                    ->getConfigurationPool()
                    ->getContainer()
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. В залi $venue немає $row ряда в секторі $venueSector!"
                    );
                throw new ModelManagerException('Error row!');
            }
            foreach ($seat as $placeAllInRow) {
                self::inspectSeatMoreThanOnePrice($row, $placeAllInRow->getPlace(), $venueSector);
            }
        }
        if ($place !== null) {
            $seat = $this->getEm()->getRepository('AppBundle:Seat')->findOneBy([
                'row' => $row,
                'place' => $place,
                'venueSector' => $venueSector,
            ]);
            if (!$seat) {
                $this
                    ->getConfigurationPool()
                    ->getContainer()
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. В залi $venue немає $row - $place в секторі $venueSector!"
                    );
                throw new ModelManagerException('Error row-place!');
            }
            self::inspectSeatMoreThanOnePrice($row, $place, $venueSector);
        }
    }

    /**
     * Search Seat with more than one price
     *
     * @param $row
     * @param $place
     * @param VenueSector $venueSector
     * @throws ModelManagerException
     */
    public function inspectSeatMoreThanOnePrice($row, $place, VenueSector $venueSector)
    {
        $seats = $this->seatPrice;
        foreach ($seats as $key) {
            if ($key === $row.'-'.$place) {
                $this
                    ->getConfigurationPool()
                    ->getContainer()
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. $row - $place в секторі $venueSector вже має цiну!"
                    );
                throw new ModelManagerException('Error Seat with more than one price!');
            }
        }
        $seats[]= $row.'-'.$place;
        $this->seatPrice = $seats;
    }

    /**
     * Search Seat without price
     *
     * @param Venue $venue
     * @return bool
     * @throws ModelManagerException
     */
    public function inspectSeatWithoutPrice(Venue $venue)
    {
        $seat = $this->getEm()->getRepository('AppBundle:Seat')->findByVenue($venue);
        if (count($seat) != count($this->seatPrice)) {
             $this
                ->getConfigurationPool()
                ->getContainer()
                ->get('session')
                ->getFlashBag()
                ->add(
                    'error',
                    "Помилка. В залi $venue ціна проставлена не на всі місця!"
                );
            throw new ModelManagerException('In the hall not all places have price!');
        }
        return true;
    }

    /**
     * SeriesNumber can not be blank!
     *
     * @param PerformanceEvent $performanceEvent
     * @return bool
     * @throws ModelManagerException
     */
    public function inspectSeriesNumber(PerformanceEvent $performanceEvent)
    {
        if (!$performanceEvent->getSeriesNumber()) {
            $this
                ->getConfigurationPool()
                ->getContainer()
                ->get('session')
                ->getFlashBag()
                ->add(
                    'error',
                    "Помилка. Введіть номер комплекта квитків!"
                );
            throw new ModelManagerException('Error SeriesNumber blank!');
        }
        return true;
    }
}
