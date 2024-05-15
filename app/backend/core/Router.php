<?php

/**
 * La classe Router gère le routage des requêtes vers les gestionnaires appropriés en fonction de l'URL demandée.
 */
class Router
{
    /**
     * @var array Tableau associatif contenant les routes et leurs gestionnaires.
     */
    private $routes = [];

    /**
     * @var callable|null Le gestionnaire par défaut à utiliser lorsque l'URL demandée ne correspond à aucune route spécifique.
     */
    private $defaultHandler;

    /**
     * @var array Tableau associatif contenant les variables globales à transmettre aux gestionnaires de route.
     */
    private $globalVariables = [];

    /**
     * Ajoute une route avec son gestionnaire associé.
     *
     * @param string $url L'URL de la route.
     * @param callable $handler Le gestionnaire de la route.
     * @return void
     */
    public function addRoute($url, $handler) {
        $this->routes[$url] = $handler;
    }

    /**
     * Définit le gestionnaire par défaut à utiliser lorsque l'URL demandée ne correspond à aucune route spécifique.
     *
     * @param callable $handler Le gestionnaire par défaut.
     * @return void
     */
    public function setDefaultHandler($handler) {
        $this->defaultHandler = $handler;
    }

    /**
     * Ajoute une variable globale à transmettre aux gestionnaires de route.
     *
     * @param string $name Le nom de la variable globale.
     * @param mixed $value La valeur de la variable globale.
     * @return void
     */
    public function addGlobalVariable($name, $value) {
        $this->globalVariables[$name] = $value;
    }

    /**
     * Gère la requête en fonction de l'URL demandée.
     *
     * @param string $url L'URL demandée.
     * @return void
     */
    public function handleRequest($url) {
        if (array_key_exists($url, $this->routes)) {
            $handler = $this->routes[$url];
            $handler();
        } else {
            $handled = false;

            // Vérifier si la route avec / facultatif existe
            $optionalSlashRoute = rtrim($url, '/'); // Supprimer le slash facultatif à la fin
            if (array_key_exists($optionalSlashRoute, $this->routes)) {
                $handler = $this->routes[$optionalSlashRoute];
                $handler();
                $handled = true;
            }

            foreach ($this->routes as $route => $handler) {
                if (strpos($route, ':') !== false) {
                    $pattern = preg_replace('/\//', '\\/', $route);
                    $pattern = '/^' . preg_replace('/\:[a-zA-Z0-9_\-]+/', '([a-zA-Z0-9_\-]+)', $pattern) . '(\/|$)/';

                    if (preg_match($pattern, $url, $matches)) {
                        $handler(...$matches);
                        $handled = true;
                        break;
                    }
                }
            }

            if (!$handled && $this->defaultHandler) {
                $defaultHandler = $this->defaultHandler;
                $globalVariables = $this->globalVariables;
                $defaultHandler(...$globalVariables);
            }
        }
    }
}