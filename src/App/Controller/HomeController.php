<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index($id)
    {
        echo $this->twig->render('index.twig.php', ['id' => $id]);
    }
}