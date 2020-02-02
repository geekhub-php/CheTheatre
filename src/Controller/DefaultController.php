<?php

namespace App\Controller;

use App\Form\Type\FormLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function loginAction(Request $request)
    {
        $session = $this->get('session');

        $form = $this->createForm(FormLoginType::class);

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
