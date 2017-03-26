<?php

namespace AppBundle\Services;

class GuzzleClientFacebook
{
    /**
     * @param $accessToken
     *
     * @return object
     */
    public function getUserFacebook($accessToken)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->get('https://graph.facebook.com/v2.8/me',
            [
                'query' => [
                    'access_token' => $accessToken,
                    'fields' => 'id, email, first_name, last_name',
              ], ]);

        $userFacebook = json_decode($res->getBody());

        return $userFacebook;
    }
}
