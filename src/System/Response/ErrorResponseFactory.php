<?php

declare(strict_types=1);

namespace System\Response;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use System\Exception\ValidationException;

class ErrorResponseFactory
{
    protected ProblemDetailsResponseFactory $problemFactory;

    public function __construct(ProblemDetailsResponseFactory $problemFactory)
    {
        $this->problemFactory = $problemFactory;
    }

    public function fromViolations(
        ConstraintViolationListInterface $violations,
        ServerRequestInterface $request
    ): ResponseInterface {
        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errors[] = [
                'path'    => $violation->getPropertyPath(),
                'code'    => $violation->getCode(),
                'message' => $violation->getMessage(),
            ];
        }

        $exception = ValidationException::create()->setInvalidParams($errors);

        return $this->problemFactory->createResponseFromThrowable($request, $exception);
    }
}
