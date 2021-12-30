<?php

declare(strict_types=1);

namespace Api\Handler;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use VK\Client\VKApiClient;

class VkApiHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var VKApiClient $apiClient */
        $apiClient = $request->getAttribute('api.client');
        $accessToken = $request->getAttribute('api.access_token');
        $groupId = $request->getAttribute('api.group_id');

//        $response = $apiClient->groups()->isMember($accessToken, [
//            'group_id' => $groupId,
//            'user_id' => 139571177
//        ]);

        return new JsonResponse([
            'response' => 'ok'
        ]);
    }
}