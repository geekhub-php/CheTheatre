<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\RowsForSale;
use AppBundle\Entity\Seat;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Venue;
use AppBundle\Entity\VenueSector;
use AppBundle\Services\Ticket\GenerateSetHandler;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PerformanceEventAdmin extends Admin
{
    protected $baseRouteName = 'AppBundle\Entity\PerformanceEvent';
    protected $baseRoutePattern = 'PerformanceEvent';
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by'    => 'dateTime',
    ];
    protected $seatPrice = [];

    /** @var GenerateSetHandler */
    protected $ticketGenerateSet;

    /**
     * PerformanceEventAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param GenerateSetHandler $ticketGenerateSet
     */
    public function __construct($code, $class, $baseControllerName, GenerateSetHandler $ticketGenerateSet)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->ticketGenerateSet = $ticketGenerateSet;
    }

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
        $queryRowsForSale = $this->getEm()->getRepository(RowsForSale::class)
            ->findVenueSectorsByPerformanceEventQueryBuilder($this->getSubject());

        $formMapper
            ->with('PerformanceEvents')
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
            ->with('PriceCategory')
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
            ->with('EnableSale')
            ->add('seriesDate', 'sonata_type_datetime_picker', [
                'dp_side_by_side'       => true,
                'dp_use_current'        => true,
                'dp_use_seconds'        => false,
                'format' => "dd/MM/yyyy HH:mm",
                'required' => true,
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
                    'required' => true,
                ])
                ->add('sale', 'checkbox', [
                    'required' => false,
                    'label' => 'Enable Sale',
                ])
                ->end()
            ;
        }
        if ($this->getSubject()->isEnableSale()) {
            $formMapper
                ->add('seriesNumber', null, [
                    'required' => true,
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
        $this->seatPrice = [];
        if (!self::inspectPriceCategories($object)) {
            return null;
        }
        if ($object->isEnableSale() === null) {
            $object->setEnableSale(false);
            $this->getEm()->persist($object);
        }
        if (($object->isEnableSale() === false) && ($object->isSale() === true)) {
            /** @var Ticket[] $tickets */
            $tickets = $this->ticketGenerateSet->handle($object);
            $this->getEm()->getRepository(Ticket::class)->batchSave($tickets);
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
        if ($object->isEnableSale()) {
            self::enableTicketsForSale($object);
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
     * Change Status in Ticket
     * form STATUS_OFFLINE to STATUS_FREE if Ticket is in RowsForSale
     * and vice versa
     *
     * @param PerformanceEvent $performanceEvent
     * @return int
     */
    public function enableTicketsForSale(PerformanceEvent $performanceEvent)
    {
        $count = $this->getEm()->getRepository(Ticket::class)->enableTicketsForSale($performanceEvent);
            $this
                ->getConfigurationPool()
                ->getContainer()
                ->get('session')
                ->getFlashBag()
                ->add(
                    'success',
                    "До продажу вiдкрито $count квиткiв!"
                );
        return $count;
    }

    /**
     * Inspect PriceCategory. Search errors
     *
     * @param PerformanceEvent $performanceEvent
     * @return bool
     */
    public function inspectPriceCategories(PerformanceEvent $performanceEvent)
    {
        $categories = $this->getEm()->getRepository('AppBundle:PriceCategory')
            ->findBy(['performanceEvent' => $performanceEvent]);
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
        foreach ($this->seatPrice as $sector => $key) {
            if ($sector === $venueSector->getId()) {
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
        }
        $seats[$venueSector->getId()][] = $row.'-'.$place;
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
        foreach ($this->seatPrice as $sector => $key) {
            $venueSector = $this->getEm()->getRepository(VenueSector::class)->find($sector);
            $seat = $this->getEm()->getRepository(Seat::class)->findBy(['venueSector' => $venueSector]);
            if (count($seat) != count($key)) {
                $this
                    ->getConfigurationPool()
                    ->getContainer()
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'error',
                        "Помилка. В залі 
                        $venue в секторі 
                        $venueSector ціна проставлена не на всі місця!"
                    );
                throw new ModelManagerException('In the hall not all places have price!');
            }
        }
        return true;
    }
}
