<?php

declare(strict_types=1);

namespace App\Handler;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $client = new Client();
        $options[RequestOptions::QUERY]['group_id'] = 34767424;
        $options[RequestOptions::QUERY]['user_id'] = 139571177;
        $options[RequestOptions::QUERY]['access_token'] = '71dbb20906984fb8db739b29c39377d1fdb1fc9f0cf4093a024bac4649462cb063563334d951ae5793b60';
        $options[RequestOptions::QUERY]['v'] = '5.131';
        $response = $client->request('GET', 'https://api.vk.com/method/groups.isMember', $options);

        return new JsonResponse([
            'status' => $response->getStatusCode(),
            'response' => json_decode($response->getBody()->getContents())
        ]);
    }
}
