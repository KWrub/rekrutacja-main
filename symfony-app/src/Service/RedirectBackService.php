<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectBackService
{
    public function __construct(
        private RouterInterface $router
    ) {}

    public function redirect(Request $request, string $defaultRoute = 'home'): RedirectResponse
    {
        $referer = $request->headers->get('referer');

        if ($referer && $this->isInternalUrl($referer, $request)) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse(
            $this->router->generate($defaultRoute)
        );
    }

    private function isInternalUrl(string $url, Request $request): bool
    {
        $parsed = parse_url($url);

        if (!isset($parsed['host'])) {
            return false;
        }

        return $parsed['host'] === $request->getHost();
    }
}