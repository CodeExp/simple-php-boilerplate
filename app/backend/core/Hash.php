<?php

/**
 * La classe Hash permet de gérer le hash pour les mots de passes et les tokens.
 */
class Hash
{
    /**
     * Génère un hachage pour une chaîne de caractères donnée.
     *
     * @param string $string La chaîne à hacher.
     * @return string Le hachage généré.
     */
    public static function make($string)
    {
        return hash(Config::get('hash/algo_name'), $string . Hash::salt());
    }

    /**
     * Génère une chaîne de sel aléatoire.
     *
     * @return string La chaîne de sel aléatoire.
     */
    public static function salt()
    {
        return random_bytes(Config::get('hash/salt'));
    }

    /**
     * Génère un hachage unique basé sur l'ID unique généré par la fonction uniqid().
     *
     * @return string Le hachage unique.
     */
    public static function unique()
    {
        return self::make(uniqid());
    }
}
