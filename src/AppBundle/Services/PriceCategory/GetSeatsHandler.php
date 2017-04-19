<?php

namespace AppBundle\Services\PriceCategory;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Seat;
use AppBundle\Repository\SeatRepository;

class GetSeatsHandler
{
    /** @var SeatRepository */
    private $seatRepository;

    /**
     * @param SeatRepository $seatRepository
     */
    public function __construct(
        SeatRepository $seatRepository
    ) {
        $this->seatRepository = $seatRepository;
    }

    /**
     * @param PriceCategory $priceCategory
     *
     * @return Seat[]
     */
    public function handle(PriceCategory $priceCategory): array
    {
        return $this->getSeatsForPriceCategory($priceCategory);
    }

    /**
     * @param PriceCategory $priceCategory
     *
     * @return Seat[]
     */
    protected function getSeatsForPriceCategory(PriceCategory $priceCategory): array
    {
        $seats = [];
        $arrayPlaces = $this->parsePriceCategoryPlaces($priceCategory);
        foreach ($arrayPlaces as $row => $places) {
            if (empty($places)) {
                $seats = array_merge(
                    $seats,
                    $this->seatRepository->getByVenueSectorAndRow($priceCategory->getVenueSector(), $row)
                );
                continue;
            }
            foreach ($places as $place) {
                $seats[] = $this->seatRepository
                    ->getByVenueSectorRowAndPlace($priceCategory->getVenueSector(), $row, $place);
            }
        }

        return $seats;
    }

    /**
     * @param PriceCategory $priceCategory
     *
     * @return array
     */
    protected function parsePriceCategoryPlaces(PriceCategory $priceCategory): array
    {
        $rows = $this->getPlacesFromString($priceCategory->getRows());
        $places = [];
        foreach ($rows as $row) {
            $places[$row] = $this->getPlacesFromString($priceCategory->getPlaces());
        }

        return $places;
    }

    /**
     * @param string $incomingStrPlaces
     *
     * @return array
     */
    protected function getPlacesFromString(string $incomingStrPlaces = null): array
    {
        $places = [];
        $dataPlaces = $this->removeEmptyElements(explode(',', $incomingStrPlaces));

        foreach ($dataPlaces as $strPlaces) {
            if (substr_count($strPlaces, '-') === 0) {
                $places[] = (int) $strPlaces;
                continue;
            }

            if (substr_count($strPlaces, '-') === 1) {
                list($begin, $end) = explode('-', $strPlaces);
                $begin = (int) $begin;
                $end = (int) $end;
                for ($place = $begin; $place <= $end; $place++) {
                    $places[] = (int) $place;
                }
            }
        }
        $this->validatePlaces($places);

        return $places;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function removeEmptyElements(array $array): array
    {
        return array_filter($array, function ($value) {
            return !empty($value);
        });
    }

    /**
     * @param array $places
     * @throws \Exception
     */
    protected function validatePlaces(array $places)
    {
        if (count(array_unique($places)) < count($places)) {
            throw new \Exception('Places arranged incorrectly. Duplicates appears.');
        }
    }
}
