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
        $id = $this->get('request')->query->get('id');
        $venue = $this->getDoctrine()->getRepository('AppBundle:Venue')->find($id);

        return $this->render('AppBundle:PerformanceEventCRUD:venue.html.twig', [
            'venue' => $venue,
        ]);
    }
}
