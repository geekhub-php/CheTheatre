<?php

namespace AppBundle\Services;
use \Facebook;

class FacebookSdk
{
 private $userNode;
 private $appId;
 private $appSecret;
 private $graf_version;
 public  $accessToken;
 public function __construct($appId, $appSecret, $graf_version){
     $this->appId=$appId;
     $this->appSecret=$appSecret;
     $this->graf_version=$graf_version;
 }
 public function setValue($accessToken)
 {
     $fb = new \Facebook\Facebook([

         'app_id' => $this->appId,
         'app_secret' => $this->appSecret,
         'default_graph_version' => $this->graf_version,
     ]);
      //$helper = $fb->getJavaScriptHelper();
     //$accessToken = $helper->getAccessToken();
     //dump($accessToken);


     $fb->setDefaultAccessToken($accessToken);

     try {
         $response = $fb->get('/me?fields=id,name,email');
         // $userNode = $response->getGraphUser();
         $this->userNode = $response->getGraphUser();
     } catch (Facebook\Exceptions\FacebookResponseException $e) {
         // When Graph returns an error
         echo 'Graph returned an error: ' . $e->getMessage();
         exit;
     } catch (Facebook\Exceptions\FacebookSDKException $e) {
         // When validation fails or other local issues
         echo 'Facebook SDK returned an error: ' . $e->getMessage();
         exit;
     }
 }
     public function getValue()
     {
         return $this->userNode;
     }
 }

