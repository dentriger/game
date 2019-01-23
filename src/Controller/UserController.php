<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Security\UserAuthenticationListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profileRender()
    {
        return $this->render('user/profile.html.twig');
    }
}
