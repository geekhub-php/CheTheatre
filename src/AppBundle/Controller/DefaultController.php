<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\FormLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function loginAction(Request $request)
    {
        /** @var Session $session */
        $session = $this->get('session');

        $form = $this->createForm(new FormLoginType());

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(':default:login.html.twig', [
            'error' => $error,
            'form'  => $form->createView(),
        ]);
    }
}
