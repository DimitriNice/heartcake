<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;




class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index()
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        return $this->render('register/register.html.twig', ['form' => $form ->createView()]);
    }
}