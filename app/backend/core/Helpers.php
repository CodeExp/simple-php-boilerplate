<?php

/**
 * Échappe une chaîne de caractères pour la sécurité HTML.
 *
 * @param string $string La chaîne à échapper.
 * @return string La chaîne échappée.
 */
function escape($string)
{
    return htmlentities(trim(stripslashes($string)), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

/**
 * Décode une chaîne de caractères échappée pour HTML.
 *
 * @param string|null $string La chaîne échappée à décoder.
 * @return string La chaîne décodée ou une chaîne vide si $string est null.
 */
function escape_decode($string)
{
    if ($string !== null) {
        return html_entity_decode($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    } else {
        return '';
    }
}

function autoload($class_name)
{
    if (is_file('app/backend/core/' . $class_name . '.php')) {
        require_once 'app/backend/core/' . $class_name . '.php';
    } else if (is_file('app/backend/classes/' . $class_name . '.php')) {
        require_once 'app/backend/classes/' . $class_name . '.php';
    }
}

/**
 * Charge automatiquement les classes lorsqu'elles sont utilisées pour la première fois.
 *
 * @param string $class_name Le nom de la classe à charger.
 * @return void
 */
function cleaner($string)
{
    return ucfirst(preg_replace('/_/', ' ', $string));
}

/**
 * Affiche le nom de l'application défini dans la configuration.
 *
 * @return void
 */
function appName()
{
    echo Config::get('app/name');
}

/**
 * Génère une chaîne aléatoire pour éviter les robots dans les adresses e-mail.
 *
 * @param int $ct Le nombre de caractères à générer.
 * @return string La chaîne aléatoire générée.
 */
function norobotmail($ct)
{
    $sort = '';
    $chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    srand((float)microtime() * 1000000);
    for ($i = 0; $i < $ct; $i++) {
        $sort .= $chaine[rand() % strlen($chaine)];
    }
    return $sort;
}

/**
 * Remplace les adresses e-mail par des balises HTML avec du texte crypté pour éviter les robots.
 *
 * @param string $str La chaîne de texte contenant les adresses e-mail à remplacer.
 * @return string La chaîne de texte avec les adresses e-mail remplacées par des balises HTML.
 */
function replacemail($str)
{
    $Str_a = norobotmail((int)rand(10, 35));
    $Str_b = norobotmail((int)rand(10, 35));
    $Str_e = norobotmail((int)rand(10, 35));
    $Str_f = norobotmail((int)rand(10, 35));
    $Str_h = norobotmail((int)rand(10, 35));
    $Str_i = norobotmail((int)rand(10, 35));
    $Str_x = norobotmail((int)rand(10, 35));
    $Str_c = norobotmail((int)rand(10, 35));
    $str = str_rot13($str);
    $Js1 = str_rot13('<a style= "color:white;" href="mailto:' . $Str_h . '" rel=\"' . $Str_h . '\">' . $Str_h . '</a>');
    $Js2 = '<span id="' . $Str_f . '"></span>' . "\r\n" . '<script type="text/javascript">' . "\r\n" . '' . $Str_a . '=new RegExp("(' . rawurlencode(str_rot13('' . $Str_h . '')) . ')","g");' . "\r\n" . '' . $Str_b . '=decodeURIComponent("' . (rawurlencode($Js1)) . '".replace(' . $Str_a . ',"' . rawurlencode(str_replace('.', '' . $Str_x . '', $str)) . '"));' . "\r\n" . '' . $Str_e . '=' . $Str_b . '.replace(/[a-zA-Z]/g, function(' . $Str_c . '){return String.fromCharCode((' . $Str_c . '<="Z"?90:122)>=(' . $Str_c . '=' . $Str_c . '.charCodeAt(0)+13)?' . $Str_c . ':' . $Str_c . '-26);});' . "\r\n" . '' . $Str_i . '=' . $Str_e . '.replace(/' . str_rot13($Str_x) . '/g,\'.\');' . "\r\n" . 'document.getElementById("' . $Str_f . '").innerHTML=' . $Str_i . ';' . "\r\n" . '</script>';
    return $Js2;
}

/**
 * Transforme une chaîne de caractères en slug.
 *
 * Cette fonction prend une chaîne de caractères et la transforme en slug en remplaçant les caractères spéciaux
 * et en convertissant les caractères accentués en caractères non accentués.
 * 
 * Cette fonction a été créée pour générer les slugs ou noms utilisés dans l'url des pages.
 *
 * @param string $text La chaîne de caractères à transformer en slug.
 * @return string Le slug généré à partir de la chaîne de caractères.
 */
function slugify($text)
{
    /*$text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
    return $text;*/

    $replace = [
        '<' => '', '>' => '', '-' => ' ', '&' => '',
        '"' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
        'Ä' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
        'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
        'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
        'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
        'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
        'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
        'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
        'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
        'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
        'Ö' => 'Oe', 'Ö' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
        'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
        'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
        'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
        'Ü' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
        'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
        'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
        'ä' => 'ae', 'ä' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
        'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
        'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
        'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
        'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
        'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
        'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
        'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
        'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
        'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
        'ö' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
        'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
        'û' => 'u', 'ü' => 'ue', 'ū' => 'u', 'ü' => 'ue', 'ů' => 'u', 'ű' => 'u',
        'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
        'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
        'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
        'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
        'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
        'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
        'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
        'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
        'ю' => 'yu', 'я' => 'ya'
    ];

    // make a human readable string
    $text = strtr($text, $replace);

    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d.]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // remove unwanted characters
    $text = preg_replace('~[^-\w.]+~', '', $text);

    return strtolower($text);
}