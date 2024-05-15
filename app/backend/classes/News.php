<?php

/**
 * La classe News représente une actualité.
 */
class News extends ModelCRUD
{

    /**
     * Constructeur de la classe News.
     */
    public function __construct()
    {
        parent::__construct('news');
    }

    /**
     * Récupère toutes les actualités à afficher actuellement.
     *
     * @return array Renvoie un tableau contenant les actualités à afficher actuellement.
     */
    public function findAllToShow()
    {
        $query = "SELECT * FROM news WHERE DATE(end_date) >= CURDATE() ORDER BY start_date DESC";
        return $this->_db->query($query)->results();
    }

    /**
     * Récupère toutes les actualités à afficher actuellement selon l'identifiant de la section.
     *
     * @param int $section L'identifiant de la section.
     * @return array Renvoie un tableau contenant les actualités à afficher actuellement pour la section spécifiée.
     * /
    public function findAllToShowBySectionId($section)
    {
        $query = "SELECT * FROM news WHERE DATE(start_date) < CURDATE() AND DATE(end_date) >= CURDATE() AND section = ? ORDER BY start_date DESC";
        return $this->_db->query($query, [$section])->results();
    }

    /**
     * Récupère toutes les actualités obsolètes selon l'identifiant de la section.
     *
     * @param int $section L'identifiant de la section.
     * @return array Renvoie un tableau contenant les actualités obsolètes pour la section spécifiée.
     * /
    public function findAllObsoletesBySectionId($section)
    {
        $query = "SELECT * FROM news WHERE DATE(end_date) < CURDATE() AND section = ? ORDER BY end_date DESC";
        return $this->_db->query($query, [$section])->results();
    }

    /**
     * Récupère toutes les actualités selon l'identifiant de la section.
     *
     * @param int $section L'identifiant de la section.
     * @return array Renvoie un tableau contenant toutes les actualités pour la section spécifiée.
     * /
    public function findBySectionId($section)
    {
        return $this->findManyBy('section', $section);
    }
    */
    
}