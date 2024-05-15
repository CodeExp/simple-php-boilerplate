<?php

/**
 * La classe Session permet de gérer les variables de session dans l'application.
 */
class Session
{
    /**
     * Vérifie si une variable de session existe.
     *
     * @param string $name Le nom de la variable de session à vérifier.
     * @return bool Retourne true si la variable de session existe, sinon false.
     */
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     * Définit une variable de session avec une valeur donnée.
     *
     * @param string $name Le nom de la variable de session.
     * @param mixed $value La valeur à assigner à la variable de session.
     * @return mixed La valeur assignée à la variable de session.
     */
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Récupère la valeur d'une variable de session.
     *
     * @param string $name Le nom de la variable de session à récupérer.
     * @return mixed La valeur de la variable de session.
     */
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    /**
     * Supprime une variable de session si elle existe.
     *
     * @param string $name Le nom de la variable de session à supprimer.
     * @return void
     */
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Stocke temporairement une valeur dans une variable de session, puis la supprime.
     *
     * @param string $name Le nom de la variable de session.
     * @param mixed $string La valeur à stocker dans la variable de session.
     * @return mixed La valeur stockée temporairement dans la variable de session, ou la valeur initiale si elle n'existait pas.
     */
    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
}
