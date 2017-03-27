<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GuzzleClient
{
    /**
     * @param string $accessToken
     *
     * @return mixed
     */
    public function getUserFacebook($accessToken)
    {
        try {
            $client = new Client();
            $result = $client->get(
                'https://graph.facebook.com/v2.8/me',
                ['query' => [
                    'access_token' => $accessToken,
                    'fields' => 'id, email, first_name, last_name']
                ]
            );
            $userFacebook = json_decode($result->getBody());

            return $userFacebook;
        } catch (TransferException $e) {
            throw new HttpException(400, 'Social login error');
        }
    }
}
