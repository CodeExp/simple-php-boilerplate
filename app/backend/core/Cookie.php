<?php

/**
 * La classe Cookie permet de gérer les cookies HTTP.
 */
class Cookie
{
    /**
     * Vérifie si un cookie existe.
     *
     * @param string $name Le nom du cookie à vérifier.
     * @return bool Retourne true si le cookie existe, sinon false.
     */
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * Obtient la valeur d'un cookie.
     *
     * @param string $name Le nom du cookie à obtenir.
     * @return mixed La valeur du cookie.
     */
    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    /**
     * Définit un cookie.
     *
     * @param string $name Le nom du cookie à définir.
     * @param mixed $value La valeur du cookie.
     * @param int $expiry La durée de validité du cookie en secondes à partir de maintenant.
     * @return bool Retourne true si le cookie est défini avec succès, sinon false.
     */
    public static function put($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    /**
     * Supprime un cookie.
     *
     * @param string $name Le nom du cookie à supprimer.
     * @return void
     */
    public static function delete($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, '', time() - 3600, '/');
    }
}
