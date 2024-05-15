<?php

/**
 * La classe ModelCRUD représente une instance.
 */
class ModelCRUD
{
    /**
     * @var Database $_db Instance de la classe Database pour la gestion de la base de données.
     * @var string $_table Nom de la table SQL utilisée dans la base de données.
     * @var array $_data Stocke les données de l'instance.
     * @var int $_count Nombre de pages trouvées dans la dernière requête.
     */
    protected $_db,
            $_table,
            $_data,
            $_count;

    /**
     * Constructeur de la classe Actualite.
     */
    public function __construct(string $table_name)
    {
        $this->_db = Database::getInstance();
        $this->_table = $table_name;
    }

    /**
     * Récupère toutes les instances.
     *
     * @return array Renvoie un tableau contenant toutes les instances.
     */
    public function findAll()
    {
        $query = "SELECT * FROM " . $this->_table;
        return $this->_db->query($query)->results();
    }

    /**
     * Récupère une instance selon son identifiant.
     *
     * @param int $id L'identifiant de l'instance.
     * @return bool Renvoie vrai si l'instance est trouvée, sinon renvoie faux.
     */
    public function findById($id)
    {
        $data = $this->_db->get($this->_table , ["id_" . $this->_table, "=", $id]);

        if ($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
            return true;
        }

        return false;
    }

    /**
     * Récupère les instances du site selon la valeur d'une colonne particulière.
     *
     * @param mixed $column La colonne utilisée pour la recherche.
     * @param mixed $value La valeur sur la recherche.
     * @return bool Renvoie vrai si des instances sont trouvées, sinon renvoie faux.
     */
    public function findManyBy($column, $value)
    {
        $query = "SELECT * FROM ? WHERE ? = ? ORDER BY placement, nom";
        $data = $this->_db->query($query, [$this->_table, $column, $value]);

        if ($data->count()) {
            $this->_data = $data->results();
            $this->_count = $data->count();
            return true;
        }

        return false;
    }

    /**
     * Récupère une instance du site selon la valeur d'une colonne.
     *
     * @param mixed $column La colonne utilisée pour la recherche.
     * @param mixed $value La valeur sur la recherche.
     * @return bool Renvoie vrai si la page est trouvée, sinon renvoie faux.
     */
    public function findOneBy($column, $value)
    {
        $data = $this->_db->get('page', [$column, "=", $value]);

        if ($data->count()) {
            $this->_data = $data->first();
            return true;
        }
    }

    /**
     * Retourne les données de l'instance après un find()
     *
     * @return array|null Les données de l'instance ou null si aucune donnée n'est définie.
     */
    public function data()
    {
        return $this->_data;
    }

    /**
     * Retourne le nombre de instances trouvées dans la dernière requête.
     *
     * @return int Le nombre de instances trouvées.
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Crée une nouvelle instance avec les données fournies.
     *
     * @param array $data Les données de l'instance à créer.
     * @throws Exception Si la création de l'instance échoue.
     */
    public function create(array $data)
    {
        if (!$this->_db->insert($this->_table, $data)) {
            throw new Exception('Impossible de créer la valeur.');
        }
    }

    /**
     * Retourne l'identifiant de la dernière insertion.
     *
     * @return int L'identifiant de la dernière insertion.
     */
    public function lastInsertId()
    {
        return $this->_db->lastInsertId();
    }

    /**
     * Met à jour une instance selon son identifiant avec les données fournies.
     *
     * @param int $id L'identifiant de l'instance à mettre à jour.
     * @param array $data Les données de l'instance à mettre à jour.
     * @throws Exception Si la mise à jour de l'instance échoue.
     */
    public function update($id, array $data)
    {
        if (!$this->_db->update($this->_table, "id_" . $this->_table, $id, $data)) {
            throw new Exception('Impossible de modifier la valeur.');
        }
    }

    /**
     * Supprime une instance selon son identifiant.
     *
     * @param int $id L'identifiant de l'instance à supprimer.
     * @throws Exception Si la suppression de l'instance échoue.
     */
    public function delete($id)
    {
        if (!$this->_db->delete($this->_table, ["id_" . $this->_table, "=", $id])) {
            throw new Exception('Impossible de supprimer la valeur.');
        }
    }
}
