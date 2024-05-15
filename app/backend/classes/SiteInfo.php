<?php

/**
 * La classe SiteInfo permet de gérer les informations uniques du site que l'on veut modifier régulièrement.
 */
class SiteInfo extends ModelCRUD
{
    /**
     * @var mixed $_value Stocke la valeur de l'information du site.
     */
    private $_value;

    /**
     * Constructeur de la classe SiteInfo.
     */
    public function __construct()
    {
        parent::__construct('site_info');
    }

    /**
     * Récupère une information du site selon son identifiant ou son nom.
     *
     * @param int|string|null $info L'identifiant numérique ou le nom de l'information du site.
     * @return bool Renvoie vrai si l'information est trouvée, sinon renvoie faux.
     */
    public function find($info = null)
    {
        if ($info) {
            $field  = (is_numeric($info)) ? 'id_site_info' : 'name';

            $data = $this->_db->get('site_info', array($field, '=', $info));

            if ($data->count()) {
                $this->_data = $data->first();
                $this->_value = $this->_data['value'];
                return true;
            }
        }
    }

    /**
     * Définit une information du site en utilisant son nom.
     *
     * @param string $name Le nom de l'information du site.
     * @param mixed $value La valeur de l'information à définir.
     * @throws Exception Si la mise à jour de l'information échoue.
     */
    public function setByName($name, $value)
    {
        $query = "INSERT INTO site_info (`name`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
        if($this->_db->query($query, array($name, $value))->error())
        {
            throw new Exception('Impossible de mettre à jour l\'information');
        }
    }

    /**
     * Supprime une information du site selon son identifiant ou son nom.
     *
     * @param int|string|null $info L'identifiant numérique ou le nom de l'information du site.
     * @throws Exception Si la suppression de l'information échoue.
     */
    public function delete($info = null)
    {
        if ($info) {
            $field  = (is_numeric($info)) ? 'id_site_info' : 'name';

            if (!$this->_db->delete("site_info", [$field, "=", $info])) {
                throw new Exception('Unable to update the user.');
            }
        }
    }

    /**
     * Retourne la valeur de l'information du site.
     *
     * @return mixed La valeur de l'information du site.
     */
    public function value()
    {
        return $this->_value;
    }
}