<?php

namespace AppBundle\Services;

class ValidatorCustomersLoginOptions
{
    public function resultOptions($userAuthenticated, $apiKeyHead, $facebookToken, $firstNameHead, $lastNameHead, $emailHead)
    {
        if ($userAuthenticated) {
            if ($facebookToken) {
                return 'social token true';
            }
            if ($firstNameHead && $lastNameHead && $emailHead) {
                return 'customer input of the form';
            } else {
                return 'Invalid email/first_name/last_name';
            }
        } elseif ($apiKeyHead) {
            return 'not valid apiKey';
        } else {
            return 'new costomer';
        }
    }
}
