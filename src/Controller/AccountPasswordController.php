<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {   

        $notification = null;

        // Obtenez l'utilisateur actuellement authentifié
        $user = $this->getUser();

        // Créez le formulairea
        $form = $this->createForm(ChangePasswordType::class, $user);
        
        $form->handleRequest($request); 
        
        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            
            // Vérifiez si le mot de passe saisi correspond au mot de passe enregistré en base de données
            if ($passwordHasher->isPasswordValid($user, $old_pwd)) {
                // Le mot de passe est valide
                
                // Récupérez le nouveau mot de passe depuis le formulaire
                $new_pwd = $form->get('new_password')->getData();
                
                // Utilisez $passwordHasher pour encoder le nouveau mot de passe
                $password = $passwordHasher->hashPassword($user, $new_pwd);
                
                // Mettez à jour le mot de passe enregistré en base de données
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $notification = 'Votre mot de passe a bien été mis à jour.';
            

                // Redirigez l'utilisateur vers une page de confirmation ou une autre action
                // Vous pouvez ajouter ici la logique de redirection
            } else {
                // Le mot de passe saisi par l'utilisateur est incorrect, vous pouvez gérer l'erreur ici
                $notification = "Votre mot de passe actuel n'est pas le bon.";

            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
