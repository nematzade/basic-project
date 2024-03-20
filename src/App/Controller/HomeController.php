<?php

namespace App\Controller;

class HomeController
{
    public function index($id)
    {
        echo "Welcome to home! $id";die;
    }
}