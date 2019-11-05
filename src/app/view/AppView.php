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


  private function renderHome(){//private
    $html = "";
    $requestedId = new \mf\utils\HttpRequest;
    if(isset($requestedId->post['recherche'])){//Si une recherche a été effectuée
      $recherche  = "%".$requestedId->post['recherche']."%";

      $data = \app\model\Media::select()->where("title","LIKE",$recherche)->orWhere("keywords","LIKE",$recherche)->orWhere("type","LIKE",$recherche)->orWhere("genre","LIKE",$recherche)->get();
    }else{
      $data = $this->data;
    }

    foreach ($data as $media) {
      $title = $media->title;
      $type = $media->type;
      $genre = $media->genre;
      $dispo = $media->disponibility;
      $picture = "data:image/jpeg;base64,".base64_encode($media->picture);


      $html.= <<<EOT
      <div class="container">
        <div class="item">
          <div class="item__img">
            <img src="${picture}" alt="${type}">
          </div>
          <div class="item__info">
            <h3 class="title">${title}</h3>
            <p class="type">${type} / ${genre}</p>
            <p class="available">${dispo}</p>
          </div>
          <!-- boîte à répeter autant de fois qu'il le faut-->
        </div>
      </div>
EOT;

    }


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
