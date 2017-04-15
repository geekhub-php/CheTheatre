<?php

namespace AppBundle\Security;

//use AppBundle\Services\CustomerLoggerSendMail;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, Logger $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param string $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get('API-Key-Token');
        if (!$apiKey) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'customer',
            $apiKey,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param string $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param string $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();
        $username = $userProvider->getUsernameByApiKey($apiKey);

        if (!$username) {
           // $this->customerLoggerSendMail;
           $this->logger->err('go. go. go');
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new HttpException(
                401,
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }
}
