<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\TemplateWrapper;

trait ResponseTrait
{
    private function constructResponse(
        array|Response $handleResponse,
        string|TemplateWrapper $twigTemplate = '',
        array $additionalParameter = [],
        int $statusCode = 200,
        array $headers = []
    ): Response {
        if ($handleResponse instanceof Response) {
            return $handleResponse;
        }

        return new Response(
            $this->twig->render($twigTemplate, array_merge($response, ['festival' => $festival])),
            $statusCode,
            $headers
        );
    }
}
