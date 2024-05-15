<?php

/**
 * La classe Newsletter représente un abonné à la newsletter.
 */
class Newsletter extends ModelCRUD
{
    /**
     * Constructeur de la classe Newsletter.
     */
    public function __construct()
    {
        parent::__construct('abonne_newsletter');
    }

    /**
     * Récupère un abonné à la newsletter selon son email.
     *
     * @param string $email L'email de l'abonné à la newsletter.
     * @return bool Renvoie vrai si l'abonné est trouvé, sinon renvoie faux.
     */
    public function findByEmail($email)
    {
        $this->findOneBy('email', $email);
    }
    
}