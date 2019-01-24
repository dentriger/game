<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;


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
