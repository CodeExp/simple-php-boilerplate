# Simple PHP Boilerplate

https://github.com/CodeExp/simple-php-boilerplate

## Sommaire
- [Concepts](#concepts)
    - [Séparation des idées](#seperation-des-idees)
        - [Backend](#backend)
        - [Frontend](#frontend)
- [Avantages](#avantages)
- [Exigences en matière de connaissances](#exigences-en-matiere-de-connaissances)
- [Vue d'ensemble](#vue-d-ensemble)
    - [Configuration centralisée](#configuration-centralisee)
    - [Gestion des routes](#gestion-des-routes)
    - [Validation facile](#validation-facile)
    - [Protection CSRF](#protection-csrf)
    - [Mot de passe sécurisé](#mot-de-passe-securise)
    - [Protection contre les injections Sql](#protection-contre-les-injections-sql)
- [Classes de base (Core)](#classes-de-base--core-)
    - [Classe Config](#classe-config)
    - [Classe Cookie](#classe-cookie)
    - [Database Classe](#classe-database)
    - [Classe Hash](#classe-hash)
    - [Fonctions Helper](#fonctions-helper)
    - [Fichier Init.php pour l'Initialisation](#fichier-init-php-pour-l-Initialisation)
    - [Classe Input](#classe-input)
    - [Classe Password](#class-password)
    - [Classe Redirect](#class-redirect)
    - [Classe Session](#classe-session)
- [Classes Utilies](#classes-utiles)
    - [Classe Token](#classe-token)
    - [Classe Validation](#classe-validation)
    - [Classe Contact](#classe-contact)
- [Classes de Modèles](#classes-de-modeles)
    - [Classe User](#classe-user)
    - [Autres classes de modèles](#autres-classes-de-modeles)
    - [Diagramme de Classes de Modèles](#diagramme-de-classes-de-modeles)
### Concepts
Le dossier de l'application `app` est composé de dossiers frontend et backend, qui séparent la conception frontend (les vues) et la logique backend (les contrôleurs et les classes de modèles).
Le dossier `medias` contient les images et documents utilisés dans l'application.
Les différentes pages sont définies dans `app/init/routes.php`. C'est dans ce fichier que l'ont défini les urls de l'application ainsi que les contrôleurs et les contenus visuels qui seront appelés pour contruire la page.
#### Séparation des idées
Le dossier "app" contient quatres dossiers :
- `frontend` : la logique de l'application, c'est-à-dire le coeur, les fichiers d'initialisation, les contrôleurs et les classes de modèles.
- `backend` : les vues de l'application, c'est à dire les fichiers qui contiennent le code HTML ainsi que les fichiers css et js.
- `setup` : les fichiers pour l'installation de l'application (notamment une sauvegarde de la base de données avec sa structure et les données pour l'année 2023).
- `vendor` : ce sont les librairies externes utilisées par l'application.
##### Backend
Le dossier backend contient les dossiers `controllers`, `init`, `classes`, `core`. Les dossiers `controllers` et `init` sont de niveau 2, Classes est de niveau 1, et Core est de niveau 0. C'est donc dans le dossier controllers que vous placerez votre logique qui se connecte à vos pages frontales. Ensuite, les dossiers `classes` sont les objets ou les outils qui sous-tendent la logique des fichiers de controllers. Enfin, le dossier `core` représente les idées abstraites qui se cachent derrière le dossier des classes. Et c'est tout.
##### Frontend
Le dossier `frontend` contient les dossiers `assets`, `includes`, `templates` et `pages` qui ont été découpés pour réutiliser votre design et vos codes. Comme le dossier assets où se trouvent les css, js, images, le dossier includes où sont séparés les headers, navbar, messages, footer et le dossier pages où se trouvent toutes les pages de l'application.

Le dossier template, quant à lui, inclus les vues spécifiques des pages (de l'entité Page) avec un design spécifique.
### Avantages
- Organiser la structure des fichiers
- Haute sécurité
- Code Réutilisable
### Exigences en matière de connaissances
- [Les bases de la programmation en PHP](https://www.youtube.com/watch?v=XKWqdp17BFo&list=PLfdtiltiRHWHjTPiFDRdTOPtSyYfz3iLW)
- [Bonne compréhension de la POO](https://www.youtube.com/watch?v=ipp4WPDwwvk&list=PLfdtiltiRHWF0RicJb20da8nECQ1jFvla) (optional)
### Vue d'ensemble
#### Configuration centralisée
```php
<?php
// app/backend/init/config.php

$GLOBALS['config'] = array(
    'app' => array(
        'name'          => 'AppName',
    ),
    'mysql' => array(
        'host'          => '127.0.0.1',
        'username'      => 'root',
        'password'      => '',
        'db_name'       => 'php_boilerplate'
    ),
...
)

<?php
// app/backend/core/Database.php
require_once 'app/backend/init/config.php';
...
$pdo = new PDO('mysql:host='.Config::get('mysql/host').';'. // 127.0.0.1
        'dbname='.Config::get('mysql/db_name'),             // php_boilerplate
                  Config::get('mysql/username'),            // root
                  Config::get('mysql/password')             // ''
    );
```
#### Gestion des routes
```php
// app/backend/init/routes.php
<?php
$router = new Router();
$router->addRoute('/accueil.php', function() use ($indexation, $user) {
    Redirect::to('/');
});
...
$router->addRoute('/', function () use ($indexation, $user) {
    require_once BACKEND_CONTROLLER  . 'accueil.php';
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'admin-navbar.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once FRONTEND_INCLUDE . 'messages.php';
    require_once FRONTEND_PAGE . 'accueil.php';
    require_once FRONTEND_INCLUDE . 'newsletter.php';
    require_once FRONTEND_INCLUDE . 'map.php';
    require_once FRONTEND_INCLUDE . 'partenaires.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});
...
$router->setDefaultHandler(function ($indexation, $user) {
    require_once BACKEND_CONTROLLER  . 'page/afficher.php';
    require_once FRONTEND_INCLUDE . 'header.php';
    require_once FRONTEND_INCLUDE . 'admin-navbar.php';
    require_once FRONTEND_INCLUDE . 'navbar.php';
    require_once FRONTEND_PAGE . 'page/afficher.php';
    require_once FRONTEND_INCLUDE . 'partenaires.php';
    require_once FRONTEND_INCLUDE . 'footer.php';
});
```
#### Validation facile
```php
<?php
// app/backend/controllers/update-account.php
require_once 'app/backend/classes/Validation.php';
$validate = new Validation();

$validation = $validate->check($_POST, array(
        'username'  => array(
            'required'  => true,
            'min'       => 2,
            'unique'    => 'users'
        ),
        'current_password'  => array(
            'required'  => true,
            'min'       => 6,
            'verify'    => 'password'
        ),
        'new_password'  => array(
            'optional'  => true,
            'min'       => 6,
            'bind'      => 'confirm_new_password'
        )
        'confirm_new_password' => array(
            'optional'  => true,
            'min'       => 6,
            'match'     => 'new_password',
            'bind'      => 'new_password',
            ),
        ));

if ($validate->passed()) {
    // Awesome Logic...
} else {
    echo $validate->$error(); // First error message.

	foreach ($validate->errors() as $error) // All error messages.
    {
        echo $error;
    }
}
...
```
#### Protection CSRF
```php
<?php
// 'app/frontend/pages/update-account.php';

require_once 'app/backend/classes/Token.php';

...
<input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
<input type="submit" value="Register me">
...

<?php
// 'app/backend/controllers/update-account.php';
require_once 'app/backend/core/Input.php';
require_once 'app/backend/classes/Token.php';
...
if (Token::check(Input::get('csrf_token'))) {
    // Do validation stuff...
}
...
```
#### Mot de passe sécurisé
```php
<?php
// app/backend/controllers/register.php

require_once app/backend/core/Password.php
...
$user->create(array(
     ...
    'password'  => Password::hash(Input::get('password')),
     ...
    ));
...

<?php
// app/backend/controllers/register.php
require_once app/backend/classes/Validation.php
...

if (Password::check($value, $this->_user->data()->password)) {
    // Do awesome stuff...
}
...
```
#### Protection contre les injections Sql
```php
<?php
// app/backend/core/Database.php
...
$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host')...);
...
$this->_query->bindvalue($x, $param);
...
$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
...
```
### Classes de base (Core)
#### Classe Config
Classe pour obtenir les informations du fichier de configuration
```php
    $tokenName = Config::get('session/token_name');
```
#### Classe Cookie
```php
<?php
require_once 'app/backend/core/Cookie.php';
// vérifier si le nom du cookie existe
if (Cookie::exists(Config::get('remember/cookie_name'))) {
    // ...
}

// obtenir la valeur du cookie
$cookie_value = Cookie::get(Config::get('remember/cookie_name'));

// supprimer la valeur du cookie
$cookie_value = Cookie::delete(Config::get('remember/cookie_name'));
```
#### Classe Database
```php
<?php
require_once 'app/backend/core/Database.php';

// Établir une connexion
$database = Database::getInstance();

// Obtenir les données de la base de données.
// $database->get($table, $where = array())
$database->get('users', array('id', '=', '13'));

// Insérer les données dans la base de données.
// $database->insert($table, $fields = array())
$database->insert('users', array(
                  'username'  => 'johnny123',
                  'password'  => Password::hash('secret'),
                  'name'      => 'JohnDoe',
                  'joined'    => date('Y-m-d H:i:s')
               ));

// Mettre à jour les données de la base de données.
// $database->update($table, $id, $fields = array())
$database->update('users', '13', array(
                  'username'  => 'newusernname',
                  'password'  => Password::hash('newsecret'),
                  'name'      => 'newname',
               ));

// Supprimer les données de la base de données.
// $database->delete($table, $where = array())
$database->delete('users', array('id', '=', '13'));

// Obtenir les premières données de la base de données.
$database->first();

// Compter les données de la base de données.
$database->count();

// Renvoie un résultat des données de l'objet à partir de la base de données.
$database->results();

// Obtenir l'erreur de la requête.
$database->error();
```
#### Classe Hash
```php
<?php
require_once 'app/backend/core/Hash.php';

// Effectuer un hachage sur la base de l'algorithme indiqué dans la configuration.
$hash = Hash::make('string');

// Générer un hachage unique.
$hash = Hash::unique();
```
#### Fonctions Helpers
```php
<?php
require_once 'app/backend/core/Helpers.php';
...

escape('string');       // convertir les entités html en guillemets.
escape_decode('string');// fonction inverse de escape()
autoload($classname);   // pour inscrire automatiquement toutes les classes.
cleaner('string');      // supprimer le /_/ puis rendre supérieure la chaîne.
appName();              // Affichage du nom de l'application
debug('string', 'int'); // fonction debug qui a été codé par Catherine
norobotmail('int');     // fonction
replacemail('string');; // fonction pour afficher les adresses emails de manière sécurisée
test_input('string');   // fonction jerem pour contrôle de l'input mail
slugify('string');      // fonction pour créer automatiquement les slugs ou noms utilisés dans l'url
```
#### Fichier Init.php pour l'Initialisation
Fichier principal où la session est initialisée et les fichiers sont chargés
```php
<?php
session_start();
require_once 'app/backend/init/config.php';
require_once 'app/backend/core/Helpers.php';

spl_autoload_register("autoload");

require_once 'app/backend/init/cookie.php';
require_once 'app/backend/init/user.php';
```
#### Classe Input
Classe pour récupérer les valeurs obtenues en $_GET ou en $_POST
```php
<?php
require_once 'app/backend/core/Input.php';

// Vérifier si la requête est vide, par exemple post ou get.
if (Input::exists()) {
    // Do some validation..
}

// Obtenir la valeur de cette demande.
Input::get('csrf_token');
```
#### Classe Password
```php
<?php
require_once 'app/backend/core/Password.php';

// Générer un hachage basé sur les configurations.
$hash_password = Password::hash('secret');

// Vérifier le hash s'il a besoin d'être rehashé
// Cela se produira si vous avez changé la configuration du mot de passe.
if (Password::rehash('hash')) {
    // Hash the password..
}

// Vérifier le mot de passe à partir du hachage stocké dans la base de données.
if (Password::check('secret', 'hash')) {
    // Do awesome stuff...
}

// Obtenir les informations sur le hachage. Par exemple, le nom de l'algo, le coût ou le sel.
$password_info = Password::getInfo('hash');
```
#### Classe Redirect
```php
<?php
require_once 'app/backend/core/Redirect.php';

// Rediriger l'utilisateur à partir de l'emplacement spécifié.
Redirect::to('/');
```
#### Classe Session
```php
<?php
require_once 'app/backend/init/config.php';
require_once 'app/backend/core/Session.php';

// Vérifier le nom de la session s'il existe dans le serveur.
if (Session::exists(Config::get('session/session_name'))) {
    // Do awesome stuff...
}

// Obtenir la valeur du nom de la session.
$session_value = Session::get(Config::get('session/session_name'));

// Supprimer la valeur du nom de la session.
Session::delete(Config::get('session/session_name'));

// Définir un message flash à afficher à l'utilisateur
// Utiliser ceci si l'on veut envoyer un message à l'utilisateur en fonction de son opération
// flash($message-name, $message)
Session::flash('register-success', 'Thanks for registering! You can login now.');
```
### Classes Utiles
#### Classe Token
```php
<?php
require_once 'app/backend/classes/Token.php';

// Générer un jeton pour la protection csrf.
$csrf_token = Token::generate();

// Vérifier si le jeton est égal au jeton de l'utilisateur.
if (Token::check($token)) {
    // Faire plus de validation...
}
```
#### Classe Validation
```php
<?php
require_once 'app/backend/classes/Validation.php'

// Créez une nouvelle instance de Validation.
$validate   = new Validation();

//  Règles disponibles :                               Notes :
//          optional => true,               'peut être nul ou sans valeur'
//          required => true,               'ne peut être nul ou sans valeur'
//          bind     => 'input_name',       'établir une connexion avec d'autres entrées'
//          min      => 'any_number',       'la longueur minimale de cette entrée'
//          max      => 'any_number',       'la longueur maximale de cette entrée'
//          match    => 'input_name',       'vérifier si l'entrée correspond à celle d'un autre'
//          email    => true,               'Vérifier si l'adresse électronique saisie est valide'
//          alnum    => true,               'vérifier si l'entrée est alpha numérique'
//          unique   => 'table_name',       'vérifier si l'entrée est unique dans la table indiquée'
//          verify   => 'password',         'uniquement en mot de passe, vérifiez l'état actuel de la'

$validation = $validate->check($_POST, array(
                'username'  => array(
                    'required'  => true,
                    'unique' => 'users',
                    'min' => 2,
                    'max' => 20,
                    'matches' => 'password',
                    'alnum'    => true
                ),
            ));

// Vérifier s'il y a des erreurs.
if ($validate->passed()) {
    // Requête à la base de données...
} else {
    // Pour le premier message d'erreur.
    // echo $validate->$error();

    // Pour tous les messages d'erreur.
    foreach ($validate->errors() as $error)
    {
        echo $error;
    }
}
```
#### Classe Contact
Classe utilisée pour l'envoi de mails à partir du formulaire de contact.
### Classes de Modèles
Ensemble des classes spécifiques aux fonctionnalités de l'application
#### Classe User
Tables associées : users, users_session et groups.
```php
<?php
require_once 'app/backend/classes/User.php'

// Créer une instance pour l'utilisateur.
$user = new User();

// Note : Ne vous laissez pas abuser...
// La logique est la même dans la requête de la base de données.
// Il est simplement encapsulé dans la classe d'utilisateur.

// Créer une donnée utilisateur.
$user->create(array(
            'username'  => Input::get('username'),
            'password'  => Password::hash(Input::get('password')),
            'name'      => Input::get('name'),
            'joined'    => date('Y-m-d H:i:s'),
        ));

// Mettre à jour les données de l'utilisateur.
$user->update(array(
            'username'  => Input::get('username'),
            'password'  => Password::hash(Input::get('password')),
            'name'      => Input::get('name'),
            'joined'    => date('Y-m-d H:i:s'),
        ), '13');  

// Trouver l'utilisateur s'il existe.
$user->delete('13');        // supprime l'utilisateur

// Trouver l'utilisateur s'il existe.
$user->find('13');          // renvoie vrai ou faux
$user->find('johnny123');   // renvoie vrai ou faux  

// Lister les utilisateurs
$user->findAllEmails()  //renvoie l'ensemble des adresse emails
$user->findAll()        //renvoie l'ensemble des utilisateurs

// Connexion de l'utilisateur.
// Le troisième argument permet de vérifier si l'utilisateur souhaite se souvenir.
$user->login('johnny123', 'secret', true);

// Vérifier si l'utilisateur existe.
$user->exists();

// Déconnexion de l'utilisateur.
// Supprimer la session et le cookie qui y sont attachés.
$user->logout();

// Obtenir les données de l'utilisateur sous forme d'objet.
$user->data();
$user->data()->username;    // 'johnny123'
$user->data()->name;        // 'JohnDoe'

// Vérifier si l'utilisateur s'est connecté.
$user->isLoggedIn();        // Renvoyer vrai ou faux.

// Supprimer les données de la base de données.
$user->deleteMe();
```
#### Autres classes de modèles
- ...
- SiteInfo (site_info)
#### Diagramme de Classes de Modèles


