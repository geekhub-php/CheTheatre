<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{

    public function customersLoginAction(Request $request)
    {
        $apiKeyHead= $request->headers->get('apikey');
        $accessToken = $request->headers->get('token');

        if ($accessToken){



        $UserFacebook = $this->get('service_facebook_sdk');
        $UserFacebook->setValue($accessToken);

        $UserFacebook = $UserFacebook->getValue();
        //dump($UserFacebook);
        echo $UserFacebook->getId();

        $Key=rand(100000,500000);
        $apiKey=(string) $Key;

        $em=$this->getDoctrine()->getManager();
        $userFind = $em->getRepository('AppBundle:Customer')
            ->findUserByFacebookId($UserFacebook->getId());
        if($userFind){


            $userRefreshApikey = $em->getRepository('AppBundle:Customer')
                ->find($userFind[0]['id']);
            $userRefreshApikey->setApiKey($apiKey);
            $em->persist($userRefreshApikey);
            $em->flush($userRefreshApikey);

        }
        else {
            $em=$this->getDoctrine()->getManager();
            $userFindApiKey = $em->getRepository('AppBundle:Customer')
                ->findUsernameByApiKey($apiKeyHead);

            $userRefreshAll= $em->getRepository('AppBundle:Customer')
                ->find($userFindApiKey[0]['id']);

            dump($userFindApiKey);



            $userRefreshAll->setEmail($UserFacebook->getEmail());
            $userRefreshAll->setFacebookID($UserFacebook->getId());
            $Key = rand(100000, 500000);
            $apiKey = (string)$Key;
            $userRefreshAll->setApiKey($apiKey);
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRefreshAll);
            $em->flush();

         }

        }
        else{
            //add first, last name or refreshAll

        }



        return $this->render(':default:login.html.twig');
    }

    }
