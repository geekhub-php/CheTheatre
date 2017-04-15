<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Model\UserResponse;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @RouteResource("User")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @Post("/users/login/new")
     *
     * @return View
     */
    public function loginNewAction(Request $request): View
    {
        $apiKey = $request->headers->get('API-Key-Token');
        $user = $this->getUser();

        if (!$user && !$apiKey) {
            $userNew = $this->get('user_login')
                ->newUser();

            $userResponse = new UserResponse($userNew);

            return View::create($userResponse);
        }

        throw new HttpException(
            403,
            'Forbidden. You don\'t have necessary permissions for the resource'
        );
    }

    /**
     * @param Request $request
     *
     * @Post("/users/login/update")
     *
     * @return View
     */
    public function loginUpdateAction(Request $request): View
    {
        $user = $this->get('user_login')
            ->updateUser(
                $request->headers->get('API-Key-Token'),
                $request->getContent()
            );

        $userResponse = new UserResponse($user);

        return View::create($userResponse);
    }

    /**
     * @param Request $request
     *
     * @Post("/users/login/social")
     *
     * @return View
     */
    public function loginSocialAction(Request $request): View
    {
        $user = $this->get('user_login')
            ->loginSocialNetwork(
                $request->headers->get('API-Key-Token'),
                $request->getContent()
            );

        $userResponse = new UserResponse($user);

        return View::create($userResponse);
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function logoutAction(Request $request): View
    {
        $apiKey = $request->headers->get('API-Key-Token');
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['apiKey' => $apiKey]);
        $user->setApiKey(null);

        $em->flush();

        return View::create(null, 204);
    }
}
