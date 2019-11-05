<?php

namespace app\control;

/* Classe TweeterController :
*
* Réalise les algorithmes des fonctionnalités suivantes:
*
*  - afficher la liste des Tweets
*  - afficher un Tweet
*  - afficher les tweet d'un utilisateur
*  - afficher la le formulaire pour poster un Tweet
*  - afficher la liste des utilisateurs suivis
*  - évaluer un Tweet
*  - suivre un utilisateur
*
*/

class AppController extends \mf\control\AbstractController {


  /* Constructeur :
  *
  * Appelle le constructeur parent
  *
  * c.f. la classe \mf\control\AbstractController
  *
  */

  public function __construct(){
    parent::__construct();
  }


  /* Méthode viewHome :
  *
  * Réalise la fonctionnalité : afficher la liste de Tweet
  *
  */

  public function viewHome(){

    /* Algorithme :
    *
    *  1 Récupérer tout les tweet en utilisant le modèle Tweet
    *  2 Parcourir le résultat
    *      afficher le text du tweet, l'auteur et la date de création
    *  3 Retourner un block HTML qui met en forme la liste
    */
    /*$requete = \app\model\User::select()->where('id','=','4')->first();
    $lignes = $requete->borrows()->get();
    foreach ($lignes as $value) {
      var_dump($value);
    }*/
    $medias = \app\model\Media::select()->get();



    $vue = new \app\view\AppView($medias);
    $vue->render("home");
  }

  public function viewProfile(){
      $http = new \mf\utils\HttpRequest();
      $user = \app\model\User::where('id', '=', $http->get['id'])->first();
      $user_name = $user->username;
      $name = $user->surname. " " .$user->name;
      $mail = $user->mail;
      $adress = $user->address;
      $city = $user->postalcode. " " .$user->city;
      $picture = "data:image/jpeg;base64,".base64_encode($user->photo);

      $tab = array("username" => $user_name,
        "name" => $name,
        "mail" => $mail,
        "adress" => $adress,
        "city" => $city,
        "picture" => $picture
    );

      $vueUser = new \app\view\AppView($tab);
      $vueUser->render("profile");
  }

}
