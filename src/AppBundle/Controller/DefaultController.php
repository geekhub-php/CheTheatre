<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\FormLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function loginAction(Request $request)
    {
        $session = $this->get('session');

        $form = $this->createForm(new FormLoginType());

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(':default:login.html.twig', array(
            'error'         => $error,
            'form'          => $form->createView(),
        ));
    }
}
