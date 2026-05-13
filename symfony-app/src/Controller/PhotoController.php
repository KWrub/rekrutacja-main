<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\LikeService;
use App\Service\PhotoService;
use App\Service\RedirectBackService;
use App\Service\SessionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    #[Route('/photo/{id}/like', name: 'photo_like')]
    public function like(int $id, Request $request, PhotoService $photoService, UserService $userService, SessionService $sessionService, LikeService $likeService, RedirectBackService $redirectBackService): Response
    {
        $user = $userService->getCurrentUser($sessionService);

        if (!$user) {
            $this->addFlash('error', 'You must be logged in to like photos.');
            return $redirectBackService->redirect($request);
        }

        $photo = $photoService->getPhotoById($id);

        if (!$photo) {
            throw $this->createNotFoundException('Photo not found');
        }

        if ($likeService->toggleLike($user, $photo)) {
            $this->addFlash('success', 'Photo liked!');
        } else {
            $this->addFlash('info', 'Photo unliked!');
        }

        return $redirectBackService->redirect($request);
    }
}
