<?php

/**
 * La classe Database permet d'interagir avec la base de données MySQL.
 */
class Database
{
    /**
     * Instance unique de la classe Database.
     *
     * @var Database|null
     */
    private static $_instance = null;

    /**
     * Instance de PDO.
     *
     * @var PDO
     */
    private $_pdo;

    /**
     * Instance de la requête.
     *
     * @var PDOStatement
     */
    private $_query;

    /**
     * Indique si une erreur s'est produite lors de l'exécution de la requête.
     *
     * @var bool
     */
    private $_error = false;

    /**
     * Résultats de la requête.
     *
     * @var array|null
     */
    private $_results;

    /**
     * Nombre de lignes affectées par la dernière requête.
     *
     * @var int
     */
    private $_count = 0;

    /**
     * Constructeur de la classe Database.
     */
    private function __construct()
    {
        try {
            $this->_pdo = new PDO(
                'mysql:host=' . Config::get('mysql/host') . ';' .
                    'dbname=' . Config::get('mysql/db_name'),
                Config::get('mysql/username'),
                Config::get('mysql/password')
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Obtient une instance unique de la classe Database.
     *
     * @return Database Une instance de la classe Database.
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Database();
        }

        return self::$_instance;
    }

    /**
     * Exécute une requête SQL.
     *
     * @param string $sql    La requête SQL à exécuter.
     * @param array  $params Les paramètres à lier à la requête SQL (optionnel).
     *
     * @return $this L'instance de la classe Database.
     */
    public function query($sql, $params = array())
    {
        $this->_error = false;

        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;

            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindvalue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results     = $this->_query->fetchAll(PDO::FETCH_ASSOC);
                $this->_count       = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    /**
     * Exécute une action SQL (SELECT, DELETE, etc.).
     *
     * @param string $action L'action SQL à exécuter.
     * @param string $table  La table sur laquelle l'action est effectuée.
     * @param array  $where  Les conditions pour l'action SQL (optionnel).
     *
     * @return $this|false L'instance de la classe Database ou false en cas d'échec.
     */
    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators  = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    /**
     * Effectue une requête SELECT sur la base de données.
     *
     * @param string $table La table à interroger.
     * @param array  $where Les conditions pour la requête SELECT.
     *
     * @return $this|false L'instance de la classe Database ou false en cas d'échec.
     */
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Effectue une requête DELETE sur la base de données.
     *
     * @param string $table La table sur laquelle exécuter la requête DELETE.
     * @param array  $where Les conditions pour la requête DELETE.
     *
     * @return $this|false L'instance de la classe Database ou false en cas d'échec.
     */
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    /**
     * Insère une nouvelle entrée dans la base de données.
     *
     * @param string $table  La table dans laquelle insérer les données.
     * @param array  $fields Les champs à insérer avec leurs valeurs.
     *
     * @return bool True si l'insertion a réussi, false sinon.
     */
    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys   = array_keys($fields);
            $values = '';
            $x      = 1;

            foreach ($fields as $field) {
                $values .= '?';

                if ($x < count($fields)) {
                    $values .= ', ';
                }

                $x++;
            }

            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Met à jour une entrée existante dans la base de données.
     *
     * @param string $table   La table à mettre à jour.
     * @param string $id_name Le nom du champ ID utilisé pour la mise à jour.
     * @param int    $id      La valeur de l'ID à mettre à jour.
     * @param array  $fields  Les champs à mettre à jour avec leurs nouvelles valeurs.
     *
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function update($table, $id_name, $id, $fields)
    {
        $set    = '';
        $x      = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($x < count($fields)) {
                $set .= ', ';
            }

            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE {$id_name} = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    /**
     * Récupère les résultats de la dernière requête exécutée.
     *
     * @return array Les résultats de la requête sous forme de tableau associatif.
     */
    public function results()
    {
        return $this->_results;
    }

    /**
     * Récupère la première ligne de résultats de la dernière requête exécutée.
     *
     * @return array|false La première ligne de résultats sous forme de tableau associatif, ou false si aucun résultat.
     */
    public function first()
    {
        return $this->results()[0];
    }

    /**
     * Vérifie si une erreur s'est produite lors de l'exécution de la dernière requête.
     *
     * @return bool True si une erreur s'est produite, false sinon.
     */
    public function error()
    {
        return $this->_error;
    }

    /**
     * Récupère le nombre de lignes affectées par la dernière requête exécutée.
     *
     * @return int Le nombre de lignes affectées.
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Récupère l'ID de la dernière ligne insérée dans la base de données.
     *
     * @return string L'ID de la dernière ligne insérée.
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }
}
