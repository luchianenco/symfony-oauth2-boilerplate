<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 12/02/2017
 * Time: 22:19
 */

namespace AppBundle\Factory;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Client\Provider\FacebookUser;

class FacebookUserFactory implements UserFactoryInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function make(FacebookUser $facebookUser)
    {
        $email = $facebookUser->getEmail();

        $existingUser = $this->em->getRepository('AppBundle:User')->findOneBy(['facebookId' => $facebookUser->getId()]);

        if ($existingUser instanceof User) {
            return $existingUser;
        }

        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            $user->setFacebookId($facebookUser->getId());
        } else {
            $user = $this->createUser($facebookUser);
        }
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function createUser(FacebookUser $facebookUser)
    {
        $user = new User();
        $user->setFacebookId($facebookUser->getId());
        $user->setUsername($facebookUser->getEmail());
        $user->setUsernameCanonical($facebookUser->getEmail());
        $user->setEmail($facebookUser->getEmail());
        $user->setEmailCanonical($facebookUser->getEmail());
        $user->setLastLogin(new \DateTime());
        $user->setPlainPassword('123');
        $user->setEnabled(true);

        return $user;
    }
}
