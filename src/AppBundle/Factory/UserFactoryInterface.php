<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 12/02/2017
 * Time: 22:20
 */

namespace AppBundle\Factory;


use League\OAuth2\Client\Provider\FacebookUser;

interface UserFactoryInterface
{
    public function make(FacebookUser $facebookUser);
}
