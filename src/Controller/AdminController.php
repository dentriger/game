<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function getUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/{id}", name="user_profile")
     */
    public function getUserProfile($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('admin/user_profile.html.twig', ['user' => $user]);
    }

}
