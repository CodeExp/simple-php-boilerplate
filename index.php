<?php

require_once 'app/backend/core/Init.php';

define('BACKEND_CONTROLLER', 'app/backend/controllers/');
define('APP_VENDOR', 'app/vendor/');
define('FRONTEND_BASE', 'app/frontend/');
define('FRONTEND_PAGE', 'app/frontend/pages/');
define('FRONTEND_INCLUDE', 'app/frontend/includes/');
define('FRONTEND_TEMPLATE', 'app/frontend/templates/');
define('FRONTEND_INCLUDE_ERROR', 'app/frontend/includes/errors/');
define('FRONTEND_ASSET', 'app/frontend/assets/');

$indexation = ""; // De base les pages sont indexées par les moteurs de recherche. cf la fonction nestpasadmin

require_once 'app/backend/core/Router.php';
require_once 'app/backend/init/routes.php';

$router->addGlobalVariable('indexation', $indexation);
$router->addGlobalVariable('user', $user);

// Traiter la requête en fonction de l'URL
$requestUrl = parse_url($_SERVER['REQUEST_URI']);
$requestPath = $requestUrl['path'];
$router->handleRequest($requestPath);

?>