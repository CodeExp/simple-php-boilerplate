<?php

/**
 * La classe Password fournit des méthodes pour le hachage et la vérification des mots de passe.
 */
class Password
{
    /**
     * Hache un mot de passe à l'aide de l'algorithme Argon2i.
     *
     * @param string $password Le mot de passe à hacher.
     * @return string|false Le mot de passe haché, ou false en cas d'échec.
     */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }

    /**
     * Vérifie si un mot de passe correspond à un hachage donné.
     *
     * @param string $password Le mot de passe à vérifier.
     * @param string $hash Le hachage à comparer.
     * @return bool Retourne true si le mot de passe et le hash correspondent, sinon false.
     */
    public static function check($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Renvoie des informations sur le mot de passe haché donné.
     *
     * @param string $hash Le mot de passe haché.
     * @return array|false Renvoie un tableau contenant des informations sur le hachage, ou false en cas d'échec.
     */
    public static function getInfo($hash)
    {
        return password_get_info($hash);
    }
}
