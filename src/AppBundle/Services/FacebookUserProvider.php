<?php

namespace AppBundle\Services;

use AppBundle\Model\FacebookResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FacebookUserProvider
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param Serializer         $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(Serializer $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param string $accessToken
     *
     * @return object
     */
    public function getUser($accessToken)
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
            $userFacebook = $this->serializer->deserialize(
                $result->getBody()->getContents(),
                FacebookResponse::class,
                'json'
            );
            $errors = $this->validator->validate($userFacebook);

            if (count($errors) > 0) {
                throw new HttpException(400, 'Social response validation error');
            }

            return $userFacebook;
        } catch (TransferException $e) {
            throw new HttpException(400, 'Social login error');
        }
    }
}
