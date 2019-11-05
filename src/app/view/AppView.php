<?php

namespace app\view;

use mf\router\Router;

class AppView extends \mf\view\AbstractView
{

    /* Méthode renderHeader
    *
    *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
    */
    private function renderHeader()
    {
        $html = "";
        $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $objRout = new \mf\router\Router();
        $hrefBorrow = $objRout->urlFor('borrow');
        $hrefProfile = $objRout->urlFor('profile');
        $hrefHome = $objRout->urlFor('home');
        $html .= <<<EOT
<div class="flex_container">
    <form class="search" action="${hrefHome}" method="post">
      <input src="${app_root}/html/img/search.svg" width="32" height="32" type="image" alt="Recherche">
      <input type="text" name="recherche" value="" placeholder="Rechercher">
    </form>
    <nav>
      <ul class="menu">
        <li><a href="${hrefBorrow}"> <img src="${app_root}/html/img/books-stack.svg" width="32" height="32" alt="Mes emprunts"> </a> </li>
        <li><a href="${hrefProfile}">  <img src="${app_root}/html/img/user.svg" width="32" height="32" alt="Mon Profil"> </a></li>
      </ul>
    </nav>
    </div>
EOT;
        return $html;
    }

    /* Méthode renderFooter
    *
    * Retourne le fragment HTML du bas de la _template (unique pour toutes les vues)
    */
    private function renderFooter()
    {
        return 'La super app créée en Licence Pro &copy;2019';
    }


    private function renderHome()
    {//private
        $html = "";
        $requestedId = new \mf\utils\HttpRequest;
        if (isset($requestedId->post['recherche'])) {//Si une recherche a été effectuée
            $recherche = "%" . $requestedId->post['recherche'] . "%";

            $data = \app\model\Media::select()->where("title", "LIKE", $recherche)->orWhere("keywords", "LIKE", $recherche)->orWhere("type", "LIKE", $recherche)->orWhere("genre", "LIKE", $recherche)->get();
        } else {
            $data = $this->data;
        }

    $router = new \mf\router\Router();

    foreach ($data as $media) {
      $title = $media->title;
      $type = $media->type;
      $genre = $media->genre;
      $dispo = $media->disponibility;
      $picture = "data:image/jpeg;base64,".base64_encode($media->picture);
      $hrefMedia = $router->urlFor('view', ['id' => $media->id]);
            $html .= <<<EOT
      <div class="container">
        <a href="${hrefMedia}">
        <div class="item">
          <div class="item__img">
            <img src="${picture}" alt="${type}">
          </div>
          <div class="item__info">
            <h3 class="title">${title}</h3>
            <p class="type">${type} / ${genre}</p>
            <p class="available">${dispo}</p>
          </div>
          </a>
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
          <input type="password" name="password" id="password" required placeholder="Mot de passe">
          <button type="submit" name="button" class="button">Envoyer</button>
      </form>
EOT;
        return $html;
    }

    private function renderMedia()
    {
        $html = '';

        foreach ($this->data as $media) {
            $title = $media->title;
            $type = $media->type;
            $genre = $media->genre;
            $picture = "data:image/jpeg;base64,".base64_encode($media->picture);
            $desc = $media->description;
            $available = $media->disponibility;

            if ($available == 1) {
                $dispo = "Disponible";
            } else {
                $dispo = "Indisponible";
            }

            $html .= <<<EOT

    <div class="container__media">
        <img src="${picture}" alt="${type}">
        <h3>${title}</h3>
        <p class="type">${genre} / ${type}</p>
        <p class="description">${desc}</p>
        <p class="available">${dispo}</p>
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

    protected function renderBody($selector)
    {

        /*
        * voire la classe AbstractView
        *
        */
        $content = "";
        $navBar = "";
        switch ($selector) {
            case 'home':
                $navBar = $this->renderHeader();
                $content = $this->renderHome();
                break;
            case 'detailMedia':
                $navBar = $this->renderHeader();
                $content = $this->renderMedia();
                break;
            case 'login':
                $content = $this->renderLogin();
                break;
            default:
                $content = $this->renderHome();
                break;
        }


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
