<?php

namespace AppBundle\Services;

use AppBundle\Model\FacebookResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class FacebookUserProvider
{
    /**
     * @var RecursiveValidator
     */
    protected $validator;

    protected $serializer;

    public function __construct(RecursiveValidator $validator, Serializer $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }
    /**
     * @param string $accessToken
     *
     * @return 
     */
    public function getUser($accessToken)
    {
        try {
            $client = new Client();
            $result = $client->get(
                'https://graph.facebook.com/v2.8/me',
                ['query' => [
                    'access_token' => $accessToken,
                    'fields' => 'id, email, first_name, last_name', ],
                ]
            );
            // todo: Add validation facebook response
//            $userFacebook = json_decode($result->getBody(), true);
            $userFacebook = $this->serializer->deserialize(
                $result->getBody(),
                FacebookResponse::class,
                'json'
            );
            $errors = $this->validator->validate($userFacebook);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                throw new ValidatorException(sprintf('There are errors "%s"', $errorsString));
            }

            return $userFacebook;
        } catch (TransferException $e) {
            throw new HttpException(400, 'Social login error');
        }
    }
}
