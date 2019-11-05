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

  private function renderLogin(){
    $obj = new \mf\router\Router();
    $hrefSend = $obj->urlFor('checklogin');
    $html = "";
      $html.= <<<EOT
      <form action="${hrefSend}" method="post" class="connect">
          <input type="email" name="mail" id="mail" required placeholder="Mail">
          <input type="text" name="password" id="password" required placeholder="Mot de passe">
          <button type="submit" name="button" class="button">Envoyer</button>
      </form>
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

      case 'login':
      $content = $this->renderLogin();
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
