<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\TemplateWrapper;

trait ResponseTrait
{
    /**
     * @param array<string, mixed>               $handleResponse
     * @param array<string, mixed>               $additionalParameter
     * @param array<string, string|list<string>> $headers
     */
    private function constructResponse(
        array|Response $handleResponse,
        string|TemplateWrapper $twigTemplate = '',
        array $additionalParameter = [],
        int $statusCode = 200,
        array $headers = [],
    ): Response {
        if ($handleResponse instanceof Response) {
            return $handleResponse;
        }

        return new Response(
            $this->twig->render($twigTemplate, array_merge($handleResponse, $additionalParameter)),
            $statusCode,
            $headers
        );
    }
}
