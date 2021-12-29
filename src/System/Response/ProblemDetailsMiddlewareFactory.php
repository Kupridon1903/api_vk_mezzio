<?php

declare(strict_types=1);

namespace System\Response;

use Error;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use Mezzio\ProblemDetails\ProblemDetailsMiddleware;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ProblemDetailsMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProblemDetailsMiddleware
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProblemDetailsMiddleware
    {
        $middleware = new ProblemDetailsMiddleware($container->get(ProblemDetailsResponseFactory::class));
        $middleware->attachListener($this->getErrorListener($container));

        return $middleware;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return callable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getErrorListener(ContainerInterface $container): callable
    {
        /** @var Logger $logger */
        $logger = $container->get('errorChannel');
        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            if ($handler instanceof NativeMailerHandler) {
                $handler->setContentType('text/html');
            }
        }
        $logger->setHandlers($handlers);

        return static function (
            Throwable $error,
            ServerRequestInterface $request,
            ResponseInterface $response
        ) use ($logger) {
            $context = [
                'error' => [
                    'code'  => $error->getCode(),
                    'file'  => $error->getFile(),
                    'line'  => $error->getLine(),
                    'trace' => $error->getTraceAsString(),
                ],
            ];

            // если была предыдущая ошибка то ее тоже добавляем
            $previous = $error->getPrevious();
            if ($previous !== null) {
                $context['error']['previous'] = [
                    'message' => $previous->getMessage(),
                    'code'    => $previous->getCode(),
                    'file'    => $previous->getFile(),
                    'line'    => $previous->getLine(),
                ];
            }

            if ($error instanceof Error) {
                $logger->error($error->getMessage(), $context);
            } elseif (!$error instanceof ProblemDetailsExceptionInterface) {
                if ($error instanceof \Exception) {
                    $logger->critical($error->getMessage(), $context);
                } elseif ($error instanceof Throwable) {
                    $logger->warning($error->getMessage(), $context);
                }
            }
        };
    }
}
