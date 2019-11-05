<?php

namespace app\view;

class AppView extends \mf\view\AbstractView {

  /* Constructeur
  *
  * Appelle le constructeur de la classe parent
  */
  public function __construct( $data = null ){
    parent::__construct($data);
  }

  /* Méthode renderHeader
  *
  *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
  */
  private function renderHeader(){
    $html = "";
    $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
    $objRout = new \mf\router\Router();
    $hrefBorrow = $objRout->urlFor('borrow');
    $hrefProfile = $objRout->urlFor('profile');
    $hrefHome = $objRout->urlFor('home');
    $html.=<<<EOT
    <form class="search" action="${hrefHome}" method="post">
      <input type="text" name="recherche" value="" placeholder="Rechercher">
      <input src="${app_root}/html/img/search.svg" width="32" height="32" type="image" alt="Recherche">
    </form>
    <nav>
      <ul class="menu">
        <li><a href="${hrefBorrow}"> <img src="${app_root}/html/img/books-stack.svg" width="32" height="32" alt="Mes emprunts"> </a> </li>
        <li><a href="${hrefProfile}">  <img src="${app_root}/html/img/user.svg" width="32" height="32" alt="Mon Profil"> </a></li>
      </ul>
    </nav>
EOT;
    return $html;
  }

  /* Méthode renderFooter
  *
  * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
  */
  private function renderFooter(){
    return 'La super app créée en Licence Pro &copy;2019';
  }

  /* Méthode renderHome
  *
  * Vue de la fonctionalité afficher tous les Tweets.
  *
  */

  private function renderHome(){//private

    /*
    * Retourne le fragment HTML qui affiche tous les Tweets.
    *
    * L'attribut $this->data contient un tableau d'objets tweet.
    *
    */
    $html = "";
      $html.= <<<EOT
      <p>Corp de la page</p>
EOT;
    return $html;
    }




  /* Méthode renderUeserTweets
  *
  * Vue de la fonctionalité afficher tout les Tweets d'un utilisateur donné.
  *
  */



  /* Méthode renderBody
  *
  * Retourne la framgment HTML de la balise <body> elle est appelée
  * par la méthode héritée render.
  *
  */

  protected function renderBody($selector){

    /*
    * voire la classe AbstractView
    *
    */
    $content = "";
    switch ($selector) {
      case 'home':
      $content = $this->renderHome();
      break;

      default:
      $content = $this->renderHome();

      break;
    }

    $navBar = $this->renderHeader();
    $footer = $this->renderFooter();
    $html = <<<EOT
    <header> ${navBar} </header>
    <section>
      ${content}
    </section>
    <footer> ${footer} </footer>
EOT;

    return $html;
  }
}
