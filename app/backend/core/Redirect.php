<?php

/**
 * La classe Redirect fournit des méthodes pour rediriger l'utilisateur vers une nouvelle URL ou afficher des erreurs HTTP.
 */
class Redirect
{
    /**
     * Redirige l'utilisateur vers une nouvelle URL ou affiche une erreur HTTP.
     *
     * @param string|int|null $location L'URL de destination, ou le code numérique de l'erreur HTTP.
     * @return void
     */
    public static function to($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include FRONTEND_INCLUDE_ERROR . '404.php';
                        exit();
                    case 405:
                        header('HTTP/1.0 405 Method Not Allowed');
                        include FRONTEND_INCLUDE_ERROR . '405.php';
                        exit();
                        break;
                }
            }

            header('Location: ' . $location);
            exit();
        }
    }
}
