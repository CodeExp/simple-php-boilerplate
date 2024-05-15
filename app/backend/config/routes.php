<?php

$router = new Router();

$router->addRoute('/accueil.php', function () use ($indexation, $user) {
    Redirect::to('/');
});

$router->addRoute('/index.php', function () use ($indexation, $user) {
    Redirect::to('/');
});

$router->addRoute('/', function () use ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once FRONTEND_INCLUDE . 'messages.php';
    require_once FRONTEND_PAGE . 'home.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});

$router->addRoute('/login', function () use ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once BACKEND_CONTROLLER . 'auth/login.php';
    require_once FRONTEND_TEMPLATE . 'auth/login.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});

$router->addRoute('/logout', function () use ($indexation, $user) {
    require_once BACKEND_CONTROLLER . 'auth/logout.php';
});

$router->addRoute('/profile', function () use ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once BACKEND_CONTROLLER . 'auth/profile.php';
    require_once FRONTEND_TEMPLATE . 'auth/profile.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});

$router->addRoute('/register', function () use ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once BACKEND_CONTROLLER . 'auth/register.php';
    require_once FRONTEND_TEMPLATE . 'auth/register.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});

$router->addRoute('/test', function () use ($indexation, $user) {
    echo 'Hello world !';
    // $string = 'stringstringstring';
    // echo Password::hash($string);
    // echo Hash::make($string);
    // echo Token::generate();
});

$router->addRoute('/update-account', function () use ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once BACKEND_CONTROLLER . 'auth/update-account.php';
    require_once FRONTEND_TEMPLATE . 'auth/update-account.php';
    require_once FRONTEND_INCLUDE . 'footer.php';

});

$router->addRoute('/delete-account', function () use ($indexation, $user) {
    require_once BACKEND_CONTROLLER . 'auth/delete-account.php';
});

$router->setDefaultHandler(function ($indexation, $user) {
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once FRONTEND_INCLUDE . 'messages.php';
    require_once FRONTEND_PAGE . 'home.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});