<?php

namespace AppBundle\Controller;

use Luchianenco\OAuth2\Client\Provider\AmazonResourceOwner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

/**
 * Class FacebookController
 * @package AppBundle\Controller
 */
class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook")
     */
    public function connectAction()
    {
        $redirect =  $this->get('oauth2.registry')
            ->getClient('facebook_main')
            ->redirect();

        return $redirect;
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        $client = $this->get('oauth2.registry')->getClient('facebook_main');

        try {
            // the exact class depends on which provider you're using
            /** @var AmazonResourceOwner $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            $user->getName();
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());die;
        }

        return $this->render('@App/backoffice/dashboard.html.twig', []);
    }
}
