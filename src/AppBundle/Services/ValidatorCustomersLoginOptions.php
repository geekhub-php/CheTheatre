<?php

namespace AppBundle\Services;

class ValidatorCustomersLoginOptions
{
    /**
     * @param string $userAuthenticated
     * @param string $apiKeyFromRequest
     * @param string $socialNetwork
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return string
     */
    public function resultOptions($userAuthenticated, $apiKeyFromRequest, $socialNetwork, $firstName, $lastName, $email)
    {
        if ($userAuthenticated) {
            if ($socialNetwork) {
                return 'social network true';
            }
            if ($firstName && $lastName && $email) {
                return 'customer input of the form';
            } else {
                return 'Invalid email/first_name/last_name';
            }
        } elseif ($apiKeyFromRequest) {
            return 'not valid apiKey';
        } else {
            return 'new customer';
        }
    }
}
