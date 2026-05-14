<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginFormType;
use App\Service\AuthService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request, FormFactoryInterface $formFactory, AuthService $authService, SessionService $sessionService): Response
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

        $userData = $authService->authenticate($username, $token);

        if (!$userData) {
            $this->addFlash('error', 'Invalid credentials');
            return $this->redirectToRoute('home');
        }

        $sessionService->setUser($userData);

        $this->addFlash('success', 'Welcome back, ' . $userData->getUsername() . '!');

        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionService $sessionService): Response
    {
        $sessionService->clear();

        $this->addFlash('info', 'You have been logged out successfully.');

        return $this->redirectToRoute('home');
    }
}
