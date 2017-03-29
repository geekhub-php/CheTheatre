<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

class PerformanceEventCRUDController extends CRUDController
{
    /**
     * @return Response
     */
    public function getVenueAction()
    {
        $id = $this->get('request')->query->get('venue_id');
        $venue = $this->getDoctrine()->getRepository('AppBundle:Venue')->find($id);

        return $this->render('AppBundle:PerformanceEventCRUD:venueHall.html.twig', [
            'venue' => $venue,
        ]);
    }

    /**
     * @return bool|Response
     */
    public function deletePriceCategoriesAction()
    {
        $performanceEventId = $this->get('request')->query->get('performanceEvent_id');
        $em = $this->getDoctrine()->getManager();
        $performanceEvent = $em->getRepository('AppBundle:PerformanceEvent')->find($performanceEventId);
        $priceCategories = $performanceEvent->getPriceCategories();
        foreach ($priceCategories as $priceCategory) {
            $performanceEvent->removePriceCategories($priceCategory);
            $em->persist($performanceEvent);
        }
        $em->flush();
        return new Response();
    }
}
