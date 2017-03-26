<?php

namespace AppBundle\Services;

use Facebook;

class FacebookSdk
{
    /**
     * @var Facebook\GraphNodes\GraphUser
     */
    private $userNode;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * @var string
     */
    private $graphVersion;

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $graphVersion
     */
    public function __construct($appId, $appSecret, $graphVersion)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->graphVersion = $graphVersion;
    }

    /**
     * @param $accessToken
     * @return Facebook\GraphNodes\GraphUser
     * @throws \Exception
     */
    public function getUserFacebook($accessToken)
    {
        $fb = new Facebook\Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => $this->graphVersion,
        ]);
        $fb->setDefaultAccessToken($accessToken);

        try {
            $response = $fb->get('/me?fields=id,email,first_name,last_name');
            $this->userNode = $response->getGraphUser();

            return $this->userNode;
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }
    }
}
