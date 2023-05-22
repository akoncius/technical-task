<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NordJsonResponse extends JsonResponse
{
    public function __construct($content = '', int $status = Response::HTTP_NOT_FOUND, array $headers = [], bool $json = false)
    {
        parent::__construct($content, $status, $headers, $json);
    }

    public function createSuccessMessage(array $message, int $status = Response::HTTP_OK): NordJsonResponse
    {
        return new self(['data' => $message], $status);
    }

    public function createFailureMessage(array $message, int $status = Response::HTTP_CONFLICT): NordJsonResponse
    {
        $errorData = [
            'error' => [
                'message' => $message,
            ],
        ];

        return new self($errorData, $status);
    }
}
