<?php

/**
 * La classe Input permet de gérer les données d'entrée utilisateur (via les formulaires)
 */
class Input
{
    /**
     * Vérifie si des données POST ou GET existent.
     *
     * @param string $type Le type de données à vérifier ('POST' par défaut).
     * @return bool Retourne true si des données existent pour le type spécifié, sinon false.
     */
    public static function exists($type = 'POST')
    {
        switch ($type) {
            case 'POST':
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;

            case 'GET':
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Récupère une valeur spécifique des données d'entrée.
     *
     * @param string $item Le nom de la clé dont la valeur doit être récupérée.
     * @return mixed La valeur de la clé spécifiée dans les données POST, GET ou FILES, ou une chaîne vide si la clé n'existe pas.
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        } elseif (isset($_FILES[$item])) {
            return $_FILES[$item];
        }
        return '';
    }
}
