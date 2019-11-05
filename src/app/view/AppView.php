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
    $html.=<<<EOT
    <h1>Notre super app</h1>
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

    foreach ($this->data as $media) {
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

    public function renderUserView(){
      $html = "";

      $userData = $this->data;
      $user_name = $userData["username"];
      $name = $userData["name"];
      $mail = $userData["mail"];
      $adress = $userData["adress"];
      $city = $userData["city"];
      $picture = $userData["picture"];

      $html = <<<EOT
      <div id="profil">
        <form class="conntect">
        <img src="${picture}" alt="photo de l'utilsiateur">
          <div class="infos">
            <input id="name" value="${name}">
            <input id="user_name" value="${user_name}">
            <input id="mail" value="${mail}">
            <div id="where">
              <input id="adress" value="${adress}">
              <input id="city" value="${city}">
            </div>
          </div>
          <button><img src="">Modifier</button>
        </form>
      </div>


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

      case 'profile':
      $content = $this->renderUserView();
      break;

      default:
      $content = $this->renderHome();

      break;
    }

    $header = $this->renderHeader();
    $footer = $this->renderFooter();
    $html = <<<EOT
    <header> ${header} </header>
    <section>
      ${content}
    </section>
    <footer> ${footer} </footer>
EOT;

    return $html;
  }
}
