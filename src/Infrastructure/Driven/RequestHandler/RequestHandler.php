<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\RequestHandler;

use App\Infrastructure\Driven\RequestHandler\Exception\InvalidValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestHandler
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}

    public function getModelFromRequest(Request $request, string $class): mixed
    {
        try {
            $createModel = $this->serializer->deserialize($request->getContent(), $class, 'json');
        } catch (MissingConstructorArgumentsException $exception) {
            $arguments = implode(', ', $exception->getMissingConstructorArguments());

            throw new InvalidValidationException('Missing Arguments: ' . $arguments);
        }

        $violations = $this->validator->validate($createModel);

        if ($violations->count() > 0) {
            foreach ($violations as $violation) {
                throw new InvalidValidationException((string) $violation->getMessage());
            }
        }

        return $createModel;
    }
}
