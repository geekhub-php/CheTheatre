<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClientController extends CRUDController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lockAction()
    {
        $swindler = $this->admin
            ->getSubject()
            ->setBanned(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush($swindler);

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlockAction()
    {
        $swindler = $this->admin
            ->getSubject()
            ->setBanned(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush($swindler);

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
