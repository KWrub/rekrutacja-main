<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\PhotoFilterDto;
use App\Form\FilterFormType;
use App\Service\LikeService;
use App\Service\PhotoService;
use App\Service\SessionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        PhotoService $photoService,
        LikeService $likeService,
        UserService $userService,
        SessionService $sessionService
    ): Response {
        $dto = new PhotoFilterDto();

        $form = $this->createForm(FilterFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('home/index.html.twig', [
                'form' => $form->createView(),
                'photos' => [],
                'currentUser' => null,
                'userLikes' => [],
            ]);
        }

        $photos = $photoService->getPhotos($dto);
        $currentUser = $userService->getCurrentUser($sessionService);
        $userLikes = $likeService->getUserLikes($currentUser, $photos);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'photos' => $photos,
            'currentUser' => $currentUser,
            'userLikes' => $userLikes,
        ]);
    }
}
