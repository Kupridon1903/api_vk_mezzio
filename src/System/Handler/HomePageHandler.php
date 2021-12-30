<?php

declare(strict_types=1);

namespace System\Handler;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use VK\Client\VKApiClient;

class HomePageHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'response' => 'ok'
        ]);
    }
}
