<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Unit\Controller;

use EHDev\BasicsBundle\Tests\Fixture\ResponseTraitTestClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class ResponseTraitTest extends TestCase
{
    private ResponseTraitTestClass $controller;

    protected function setUp(): void
    {
        $this->controller = new ResponseTraitTestClass(new Environment(new ArrayLoader([
            'page.html.twig' => '{{ title }} - {{ subtitle }}',
        ])));
    }

    public function testReturnsGivenResponseUnchanged(): void
    {
        $response = new Response('redirect', 302);

        self::assertSame($response, $this->controller->respond($response, 'page.html.twig'));
    }

    public function testRendersTemplateWithMergedParameters(): void
    {
        $response = $this->controller->respond(
            ['title' => 'Title', 'subtitle' => 'old'],
            'page.html.twig',
            ['subtitle' => 'Subtitle'],
            201,
            ['X-Test' => 'yes'],
        );

        self::assertSame('Title - Subtitle', $response->getContent());
        self::assertSame(201, $response->getStatusCode());
        self::assertSame('yes', $response->headers->get('X-Test'));
    }
}
