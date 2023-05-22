<?php

namespace App\Shared\Controller;

use App\Shared\Response\NordJsonResponse;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function jsonResponse(string $content = '', int $status = NordJsonResponse::HTTP_NO_CONTENT, array $headers = []): NordJsonResponse
    {
        return new NordJsonResponse($content, $status, $headers);
    }
}
