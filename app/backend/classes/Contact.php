<?php

/**
 * La classe Contact gère un formulaire de contact.
 */
class Contact extends ModelCRUD
{

    /**
     * Constructeur de la classe Contact.
     *
     * @param string $nom_contact Le nom du contact.
     * @param string $prenom_contact Le prénom du contact.
     * @param string $email_contact L'adresse e-mail du contact.
     * @param string $equipe_contact L'équipe du contact.
     * @param string $question La question posée par le contact.
     */
    public function __construct($nom_contact, $prenom_contact, $email_contact, $equipe_contact, $question)
    {
        parent::__construct('contact');

        $this->setNomContact($nom_contact);
        $this->setPrenomContact($prenom_contact);
        $this->setEmailContact($email_contact);
        $this->setEquipeContact($equipe_contact);
        $this->setQuestion($question);

    }

    /**
     * Envoie un e-mail de contact à une liste de destinataires.
     *
     * @param array $contacts La liste des adresses e-mail des destinataires.
     * @throws Exception Si l'insertion des données dans la base de données échoue.
     */
    public function sendEmailContact($contacts = array())
    {
        $this->insert();
        
        // structure du mail 
        $mailtopsc = "
        <html>
            <head>
                <title>Contact sur le site</title>
            </head>
            <body>
                <h1>Prise de Contact sur le site Pays sud Charente</h1>
                <p><b>Nom :</b>";
                    $mailtopsc .= $this->_data['nom_contact'];
                    $mailtopsc .= "</p>
                <p><b>Prénom :</b>";
                    $mailtopsc .= $this->_data['prenom_contact'];
                    $mailtopsc .= "</p>
                <p><b>Email :</b>";
                    $mailtopsc .= $this->_data['email_contact'];
                    $mailtopsc .= "</p>
                <p><b>Question :</b>";
                    $mailtopsc .= html_entity_decode($this->_data['question']);
                    $mailtopsc .= "</p>
            </body>
        </html>
        ";

        // Toujours définir le type de contenu lors de l'envoi d'un courrier électronique au format HTML
        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        foreach ($contacts as $contact) {
            mail($contact, 'Mail envoyé par le site pays-sud-charente.com', $mailtopsc, $header);
        }
    }

    /**
     * Insère les données du contact dans la base de données.
     *
     * @throws Exception Si l'insertion des données échoue.
     */
    private function insert()
    {
        $this->create($this->_data);
    }

    // Setters pour les propriétés

    /**
     * Définit le nom du contact.
     *
     * @param string $nom_contact Le nom du contact.
     */
    public function setNomContact($nom_contact)
    {
        $this->_data['nom_contact'] = strip_tags($nom_contact);
    }

    /**
     * Définit le prénom du contact.
     *
     * @param string $prenom_contact Le prénom du contact.
     */
    public function setPrenomContact($prenom_contact)
    {
        $this->_data['prenom_contact'] = strip_tags($prenom_contact);
    }

    /**
     * Définit l'adresse e-mail du contact.
     *
     * @param string $email_contact L'adresse e-mail du contact.
     */
    public function setEmailContact($email_contact)
    {
        $this->_data['email_contact'] = strip_tags($email_contact);
    }

    /**
     * Définit l'équipe du contact.
     *
     * @param string $equipe_contact L'équipe du contact.
     */
    public function setEquipeContact($equipe_contact)
    {
        $this->_data['equipe_contact'] = strip_tags($equipe_contact);
    }

    /**
     * Définit la question posée par le contact.
     *
     * @param string $question La question posée par le contact.
     */
    public function setQuestion($question)
    {
        $this->_data['question'] = htmlspecialchars($question);
    }
}