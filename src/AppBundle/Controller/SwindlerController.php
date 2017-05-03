<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SwindlerController extends CRUDController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lockAction()
    {
        $performanceEventId = $this->get('request')->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $swindler = $em->getRepository('AppBundle:Swindler')->find($performanceEventId);
        $swindler->setBanned(true);
        $em->flush($swindler);

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlockAction()
    {
        $performanceEventId = $this->get('request')->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $swindler = $em->getRepository('AppBundle:Swindler')->find($performanceEventId);
        $swindler->setBanned(false);
        $em->flush($swindler);

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
