<?php

namespace AppBundle\Services;

use AppBundle\Model\UserRequest;
use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\User;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserLogin
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var FacebookUserProvider
     */
    private $facebookUserProvider;

    /**
     * @param ManagerRegistry      $registry
     * @param Serializer           $serializer
     * @param ValidatorInterface   $validator
     * @param FacebookUserProvider $facebookUserProvider
     */
    public function __construct(
        ManagerRegistry $registry,
        Serializer $serializer,
        ValidatorInterface $validator,
        FacebookUserProvider $facebookUserProvider
    ) {
        $this->registry = $registry;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->facebookUserProvider = $facebookUserProvider;
    }

    /**
     * @return User
     */
    public function newUser(): User
    {
        do {
            $apiKey = uniqid('token_');
            $user = new User();
            $user->setUsername('user');
            $user->setApiKey($apiKey);
            $user->setRole('ROLE_API');
        } while (!$this->isValidUserData($user, ['uniqApikey']));

        $this->registry->getManager()->persist($user);
        $this->registry->getManager()->flush();

        return $user;
    }

    /**
     * @param string $apiKey
     * @param string $content
     *
     * @return User
     */
    public function updateUser(string $apiKey, string $content): User
    {
        $userRequest = $this->serializer->deserialize(
            $content,
            UserRequest::class,
            'json'
        );

        if (!$this->isValidUserData($userRequest, ['update'])) {
            throw new HttpException(400, 'Validation error');
        }

        $user = $this->registry->getRepository('AppBundle:User')
            ->findOneBy(['apiKey' => $apiKey]);
        $user->setApiKey($apiKey);
        $user->setFirstName($userRequest->getFirstName());
        $user->setLastName($userRequest->getLastName());
        $user->setEmail($userRequest->getEmail());

        $this->registry->getManager()->flush();

        return $user;
    }

    /**
     * @param string $apiKey
     * @param string $content
     *
     * @return User
     */
    public function loginSocialNetwork(string $apiKey, string $content): User
    {
        $userRequest = $this->serializer->deserialize(
            $content,
            UserRequest::class,
            'json'
        );

        if (!$this->isValidUserData($userRequest, ['socialNetwork'])) {
            throw new HttpException(400, 'Validation error');
        }

        $userSocialResponse = $this->facebookUserProvider
            ->getUser($userRequest->getSocialToken());

        $userFacebook = $this->registry->getRepository('AppBundle:User')
            ->findOneBy(['facebookId' => $userSocialResponse->getId()]);
        $user = $this->registry->getRepository('AppBundle:User')
            ->findOneBy(['apiKey' => $apiKey]);

        if ($userFacebook && ($userFacebook->getApiKey() != $apiKey)) {
            $this->registry->getManager()->remove($userFacebook);
            $this->registry->getManager()->flush();
        }

        $user->setFacebookId($userSocialResponse->getId());
        $user->setEmail($userSocialResponse->getEmail());
        $user->setFirstName($userSocialResponse->getFirstName());
        $user->setLastName($userSocialResponse->getLastName());
        $this->registry->getManager()->flush();

        return $user;
    }

    /**
     * @param object $user
     * @param array  $groups
     *
     * @return bool
     */
    private function isValidUserData($user, array $groups): bool
    {
        $errors = $this->validator->validate($user, null, $groups);

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}
