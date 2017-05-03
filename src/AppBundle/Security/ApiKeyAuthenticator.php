<?php

namespace AppBundle\Security;

use AppBundle\Entity\Swindler;
use Monolog\Logger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ManagerRegistry $registry, Logger $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $swindler = $this->registry->getRepository('AppBundle:Swindler')
            ->findSwindlerIsBanned($request->getClientIp());

        if ($swindler) {
            throw new HttpException(403, 'Forbidden. You\'re banned!');
        }

        if (!$token = $request->headers->get('API-Key-Token')) {
            return null;
        }

        return array(
            'token' => $token,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        $user = $this->registry->getRepository('AppBundle:User')
            ->findOneBy(['apiKey' => $apiKey]);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->saveSwindler($request);
        $data = [
            'code' => '403',
            'message' => 'Forbidden. You don\'t have necessary permissions for the resource',
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'code' => '401',
            'message' => 'Authentication required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    private function saveSwindler($request)
    {
        $swindler = $this->registry->getRepository('AppBundle:Swindler')
            ->findOneBy(['ip' => $request->getClientIp()]);

        if ($swindler) {
            $countAttempts = $swindler->getCountAttempts();
            $swindler->setCountAttempts(++$countAttempts);
            $this->registry->getManager()->flush();
        } else {
            $swindler = new Swindler();
            $swindler->setCountAttempts(1);
            $swindler->setIp($request->getClientIp());
            $swindler->setBanned(false);
            $this->registry->getManager()->persist($swindler);
            $this->registry->getManager()->flush();
        }

        if (($swindler->getCountAttempts() % 50 == 0) || $swindler->getCountAttempts() == 1) {
            $this->logger->err('403. api_key not valid!');
        }
    }
}
