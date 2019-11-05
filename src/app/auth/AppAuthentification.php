<?php

namespace app\auth;

class AppAuthentification extends \mf\auth\Authentification {

    /*
     * Classe TweeterAuthentification qui définie les méthodes qui dépendent
     * de l'application (liée à la manipulation du modèle User)
     *
     */

    /* niveaux d'accès de TweeterApp
     *
     * Le niveau USER correspond a un utilisateur inscrit avec un compte
     * Le niveau ADMIN est un plus haut niveau (non utilisé ici)
     *
     * Ne pas oublier le niveau NONE un utilisateur non inscrit est hérité
     * depuis AbstractAuthentification
     */
    const ACCESS_LEVEL_USER  = 100;
    const ACCESS_LEVEL_ADMIN = 200;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }

    /* La méthode createUser
     *
     *  Permet la création d'un nouvel utilisateur de l'application
     *
     *
     * @param : $username : le nom d'utilisateur choisi
     * @param : $pass : le mot de passe choisi
     * @param : $fullname : le nom complet
     * @param : $level : le niveaux d'accès (par défaut ACCESS_LEVEL_USER)
     *
     * Algorithme :
     *
     *  Si un utilisateur avec le même nom d'utilisateur existe déjà en BD
     *     - soulever une exception
     *  Sinon
     *     - créer un nouveeau modèle User avec les valeurs en paramètre
     *       ATTENTION : Le mot de passe ne doit pas être enregistré en clair.
     *
     */

    public function createUser($username, $pass, $fullname,$level=self::ACCESS_LEVEL_USER) {
      $requeteTw = \app\model\User::select()->where('username','=',$username)->first();
      if($requeteTw == $username){
        throw new mf\auth\exception\AuthentificationException('Nom existant en BDD');
      }
      else{
        $newuser = new \app\model\User ;
        $newuser->fullname = $fullname;
        $newuser->pass = $this->hashPassword($password);
        $newuser->username = $username;
        $newuser->save();
      }
    }

    /* La méthode loginUser
     *
     * permet de connecter un utilisateur qui a fourni son nom d'utilisateur
     * et son mot de passe (depuis un formulaire de connexion)
     *
     * @param : $username : le nom d'utilisateur
     * @param : $password : le mot de passe tapé sur le formulaire
     *
     * Algorithme :
     *
     *  - Récupérer l'utilisateur avec l'identifiant $username depuis la BD
     *  - Si aucun de trouvé
     *      - soulever une exception
     *  - sinon
     *      - réaliser l'authentification et la connexion (cf. la class Authentification)
     *
     */

    public function loginUser($mail, $password){
      $requete = \app\model\User::select()->where('mail','=',$mail);
      if(empty($requete)){
        throw new \mf\auth\exception\AuthentificationException('Utilisateur non trouvé');
      }
      else{
        $requete = \app\model\User::select()->where('mail','=',$mail)->first();
        $db_password = $requete->password;
        $levelUser = $requete->level;
        $this->login($mail,$db_password,$password,$levelUser);
      }
    }

}
