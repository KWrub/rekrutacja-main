<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginFormType;
use App\Repository\AuthTokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    // TODO: zmiana z geta na post
    #[Route('/auth', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request, FormFactoryInterface $formFactory, AuthTokenRepository $authTokenRepository, UserRepository $userRepository): Response
    {
        $form = $formFactory->create(LoginFormType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            $this->addFlash('error', 'You have to fill entire form!');
            return $this->redirectToRoute('home');
        }

        $data = $form->getData();
        $token = $data['token'];
        $username = $data['username'];

        $authToken = $authTokenRepository->findByToken($token);

        if (!$authToken) {
            return new Response('Invalid token', 401);
        }

        $userData = $userRepository->findByUsername($username);

        if (!$userData) {
            return new Response('User not found', 404);
        }

        $session = $request->getSession();
        $session->set('user_id', $userData->getId());
        $session->set('username', $userData->getUsername());

        $this->addFlash('success', 'Welcome back, ' . $userData->getUsername() . '!');

        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();

        $this->addFlash('info', 'You have been logged out successfully.');

        return $this->redirectToRoute('home');
    }
}
