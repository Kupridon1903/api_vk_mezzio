<?php

namespace Api\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use VK\Client\VKApiClient;

class ApiClientMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Версия API
        $apiVersion = $this->container->get('config')['api']['api_version'];
        $apiClient = new VKApiClient($apiVersion);

        $accessToken = $this->container->get('config')['api']['access_token'];
        $request = $request->withAttribute('api.access_token', $accessToken);

        $groupId = $this->container->get('config')['api']['group_id'];
        $request = $request->withAttribute('api.group_id', $groupId);

        return $handler->handle($request->withAttribute('api.client', $apiClient));
    }
}