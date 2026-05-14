<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private function createUser(string $token = 'existing-token'): User
    {
        $container = self::getContainer();
        $em = $container->get('doctrine')->getManager();

        $user = new User();

        $user->setUsername('test-user-' . uniqid());
        $user->setEmail('test-' . uniqid() . '@example.com');

        $user->setPhoenixAppToken($token);

        $em->persist($user);
        $em->flush();

        return $user;
    }

    private function reloadUser(User $user): User
    {
        $em = self::getContainer()->get('doctrine')->getManager();

        $em->clear();

        return $em->find(User::class, $user->getId());
    }

    public function testProfileRedirectsWhenUserNotLoggedIn(): void
    {
        $client = static::createClient();

        $sessionService = $this->createMock(SessionService::class);

        $sessionService->method('getUserId')->willReturn(null);
        $sessionService->expects($this->once())
            ->method('clear');

        static::getContainer()->set(SessionService::class, $sessionService);

        $client->request('GET', '/profile');

        $this->assertResponseRedirects('/');
    }

    public function testProfilePageRendersForLoggedUser(): void
    {
        $client = static::createClient();

        $user = $this->createUser();

        $sessionService = $this->createMock(SessionService::class);

        $sessionService->method('getUserId')->willReturn($user->getId());

        static::getContainer()->set(SessionService::class, $sessionService);

        $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testUpdateProfileRedirectsWhenUserNotLoggedIn(): void
    {
        $client = static::createClient();

        $sessionService = $this->createMock(SessionService::class);

        $sessionService->method('getUserId')->willReturn(null);
        $sessionService->expects($this->once())
            ->method('clear');

        static::getContainer()->set(SessionService::class, $sessionService);

        $client->request('POST', '/profile/update');

        $this->assertResponseRedirects('/');
    }

    public function testUpdateProfileValidationFailsDoesNotChangeDatabase(): void
    {
        $client = static::createClient();

        $user = $this->createUser('existing-token');

        $sessionService = $this->createMock(SessionService::class);

        $sessionService->method('getUserId')->willReturn($user->getId());

        static::getContainer()->set(SessionService::class, $sessionService);

        $client->request('POST', '/profile/update', [
            'update_user_profile_form' => [
                'phoenixAppToken' => '', // invalid
            ],
        ]);

        $this->assertResponseRedirects('/profile');

        $reloaded = $this->reloadUser($user);

        $this->assertSame('existing-token', $reloaded->getPhoenixAppToken());
    }

    public function testUpdateProfileSuccessPersistsToDatabase(): void
    {
        $client = static::createClient();

        $user = $this->createUser('existing-token');

        $sessionService = $this->createMock(SessionService::class);

        $sessionService->method('getUserId')->willReturn($user->getId());

        static::getContainer()->set(SessionService::class, $sessionService);

        $client->request('POST', '/profile/update', [
            'update_user_profile_form' => [
                'phoenixAppToken' => 'valid-token',
            ],
        ]);
        $this->assertResponseRedirects('/profile');

        $reloaded = $this->reloadUser($user);

        $this->assertSame('valid-token', $reloaded->getPhoenixAppToken());
    }
}