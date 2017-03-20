<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    public function customersLoginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apiKeyHead = $request->headers->get('API-Key-Token');
        $facebookToken = $request->headers->get('social_token');
        $apiKey = uniqid('token_');
        if ($facebookToken) {
            $UserFacebook = $this->get('service_facebook_sdk')->setValue($facebookToken)->getValue();
            $userFind = $em->getRepository('AppBundle:Customer')
              ->findUserByFacebookId($UserFacebook - getId());
            if ($userFind) {
                $userFind->setApiKey($apiKey);
                $em->flush();
            } else {
                $userFindApiKey = $em->getRepository('AppBundle:Customer')
                   ->findUsernameByApiKey($apiKeyHead);
                $userFindApiKey->setEmail($UserFacebook->getEmail());
                $userFindApiKey->setFacebookID($UserFacebook->getId());
                $em->flush();
            }
        } else {
            //add first, last name or refreshAll
        }

        return $this->render(':default:login.html.twig');
    }
}
