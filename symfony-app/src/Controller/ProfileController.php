<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UpdateUserProfileDto;
use App\Form\UpdateUserProfileFormType;
use App\Service\PhotoImportService;
use App\Service\SessionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(UserService $userService, SessionService $sessionService): Response
    {
        $user = $userService->getCurrentUser($sessionService);

        if (!$user) {
            $sessionService->clear();
            return $this->redirectToRoute('home');
        }

        $dto = new UpdateUserProfileDto();
        $dto->phoenixAppToken = $user->getPhoenixAppToken() ?? '';

        $form = $this->createForm(UpdateUserProfileFormType::class, $dto);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/update', name: 'profile_update', methods: ['POST'])]
    public function updateProfile(Request $request, UserService $userService, SessionService $sessionService): Response
    {
        $user = $userService->getCurrentUser($sessionService);

        if (!$user) {
            $sessionService->clear();
            return $this->redirectToRoute('home');
        }

        $dto = new UpdateUserProfileDto();
        $form = $this->createForm(UpdateUserProfileFormType::class, $dto);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('error', 'Invalid form submission.');
            return $this->redirectToRoute('profile');
        }

        $userService->updateUserProfile($user, $dto->phoenixAppToken);

        $this->addFlash('success', 'Your profile has been updated successfully.');

        return $this->redirectToRoute('profile');
    }

    #[Route('/profile/import-photos', name: 'profile_import_photos', methods: ['POST'])]
    public function importPhotos(UserService $userService, PhotoImportService $photoImportService, SessionService $sessionService): Response
    {
        $user = $userService->getCurrentUser($sessionService);

        if (!$user) {
            $sessionService->clear();
            return $this->redirectToRoute('home');
        }

        try {
            $importedCount = $photoImportService->importPhotosFromPhoenix($user);
            $this->addFlash('success', sprintf('Successfully imported %d photos from Phoenix API.', $importedCount));
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to import photos: ' . $e->getMessage());
        }

        return $this->redirectToRoute('profile');
    }
}

