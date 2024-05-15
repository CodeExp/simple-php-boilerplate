<?php

/**
 * Classe pour récupérer les informations de configuration.
 */
class Config
{
    /**
     * Retourne les données de configuration.
     *
     * @return mixed|false Les données de configuration ou false si celle-ci n'est pas définit.
     */
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;
        }

        return false;
    }
}
