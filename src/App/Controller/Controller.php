<?php

namespace App\Controller;

class Controller
{
    protected \Twig\Environment $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(realpath(__DIR__.'/../') .'/resource/views/');
        $this->twig = new \Twig\Environment($loader);
    }
}