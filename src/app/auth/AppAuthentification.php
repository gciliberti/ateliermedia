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

    public function createUser($mail,$name,$surname,$username,$address,$postalcode,$city,$phone, $password,$level=self::ACCESS_LEVEL_USER) {
      $user = \app\model\User::select()->where('mail','=',$mail)->first();
      if($user == $username){
        throw new mf\auth\exception\AuthentificationException('Mail existant en BDD');
      }
      else{
        $newuser = new \app\model\User ;
        $newuser->password = $this->hashPassword($password);
        $newuser->surname = $surname;
        $newuser->name = $name;
        $newuser->mail = $mail;
        $newuser->phone = $phone;
        $newuser->address = $address;
        $newuser->postalcode = $postalcode;
        $newuser->city = $city;
        $newuser->subscription_date = $date = date('Y-m-d H:i:s');
        $newuser->isvalidated = 0;
        $newuser->level = self::ACCESS_LEVEL_USER;
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
      $user = \app\model\User::select()->where('mail','=',$mail)->first();
      if(empty($user)){
        throw new \mf\auth\exception\AuthentificationException('Utilisateur non trouvé');
      }
      else{
        $db_password = $user->password;
        $levelUser = $user->level;
        $this->login($mail,$db_password,$password,$levelUser);
      }
    }

}
