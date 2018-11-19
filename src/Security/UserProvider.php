<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09.09.2018
 * Time: 17:15
 */

namespace App\Security;


use App\Controller\UserController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function getUsernameForApiKey($apiKey)
    {
        $username = 'ad';

        return $username;
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->loadUserByEmail($username);
    }

    public function refreshUser(UserInterface $user)
    {
        $user = $this->loadUserByUsername($user->getEmail());
        return $user;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userRepository->findOneBy(['uid' => $response->getUsername()]);
        if(is_null($user)) {
            $user = $this->userRepository->createUserFromResponse($response);
        }

        return $user;
    }
}