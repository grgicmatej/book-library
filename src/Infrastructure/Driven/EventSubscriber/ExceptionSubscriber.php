<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\EventSubscriber;

use App\Infrastructure\Driven\RequestHandler\Exception\InvalidValidationException;
use Assert\AssertionFailedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    /** @return array<string, string> */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'buildErrorResponse',
        ];
    }

    public function buildErrorResponse(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        dd($exception);
        $response = match (true) {
            $exception instanceof AccessDeniedException,
            $exception instanceof UnauthorizedHttpException => new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_UNAUTHORIZED,
            ),
            $exception instanceof InvalidValidationException,
            $exception instanceof AssertionFailedException => new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST,
            ),
            $exception instanceof NotNormalizableValueException => new JsonResponse(
                ['error' => sprintf('The type of the "%s" field must be one of "%s" ("%s" given).', $exception->getPath(), implode('", "', array_values($exception->getExpectedTypes() ?? [])), $exception->getCurrentType())],
                Response::HTTP_BAD_REQUEST,
            ),

            $exception instanceof ConflictHttpException => new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_CONFLICT,
            ),

            $exception instanceof NotFoundHttpException => new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_NOT_FOUND,
            ),
            default => null,
        };

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
