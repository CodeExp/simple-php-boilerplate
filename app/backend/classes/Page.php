<?php

/**
 * La classe Page représente une page du site.
 */
class Page extends ModelCRUD
{
    /**
     * Récupère une page du site selon son nom.
     *
     * @param string $nom Le nom de la page.
     * @return bool Renvoie vrai si la page est trouvée, sinon renvoie faux.
     */
    public function findByNom($nom)
    {
        return $this->findOneBy('nom', $nom);
    }

    /**
     * Récupère les pages du site selon l'identifiant de la section.
     *
     * @param int $section L'identifiant de la section.
     * @return bool Renvoie vrai si des pages sont trouvées, sinon renvoie faux.
     * /
    public function findBySectionId($section)
    {
        return $this->findManyBy("section", $section);
    }
    */
}