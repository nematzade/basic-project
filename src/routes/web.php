<?php

$router = new \App\lib\Router();

// todo: route name
$router->addRoute("GET", "/home/:id", [\App\Controller\HomeController::class, 'index']);

$router->addRoute("GET", '/', function () {
    echo "Welcome to main page!";
    exit;
});

$router->addRoute("GET", '/index/:id/:name', function ($id, $name) {
    echo "My id is: $id and my name is $name";
    exit;
});

$router->matchRoute();