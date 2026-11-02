<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Fixture;

use EHDev\BasicsBundle\Controller\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ResponseTraitTestClass
{
    use ResponseTrait;

    public function __construct(
        private readonly Environment $twig,
    ) {}

    /**
     * @param array<string, mixed>               $handleResponse
     * @param array<string, mixed>               $additionalParameter
     * @param array<string, string|list<string>> $headers
     */
    public function respond(
        array|Response $handleResponse,
        string $template = '',
        array $additionalParameter = [],
        int $statusCode = 200,
        array $headers = [],
    ): Response {
        return $this->constructResponse($handleResponse, $template, $additionalParameter, $statusCode, $headers);
    }
}
