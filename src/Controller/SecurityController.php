<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user/insert', name: 'insertuser')]
    public function insertUser(EntityManagerInterface $doctrine, UserPasswordHasherInterface $hasher): Response
    {
        $user = new Usuario();
        $user->setEmail('admin@test.com');
        $user->setPassword($hasher->hashPassword($user, '1234'));
        $user->setName('Admin');
        $user->setRoles(['ROLE_ADMIN']);

        $doctrine->persist($user);
        $doctrine->flush();

        return new Response('Usuario creado correctamente');
    }

    #[Route('/user/new', name: 'newuser')]
    public function newUser(EntityManagerInterface $doctrine, Request $request, UserPasswordHasherInterface $hasher)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $doctrine->persist($user);
            $doctrine->flush();
            $this->addFlash("success", "Usuario creado correctamente");

            return $this->redirectToRoute("getbooks");
        }
        return $this->renderForm("books/newuser.html.twig", ["userForm" => $form]);
    }
}
