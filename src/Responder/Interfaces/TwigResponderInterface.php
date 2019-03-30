<?php

namespace App\Responder\Interfaces;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

interface TwigResponderInterface
{
    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator);

    public function __invoke(string $view, array $args = []);
}
