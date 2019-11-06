<?php

namespace app\view;

use mf\router\Router;

class AppView extends \mf\view\AbstractView
{

    private function renderHeader()
    {
        $html = "";
        $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $objRout = new \mf\router\Router();
        $hrefBorrow = $objRout->urlFor('borrow');
        $hrefProfile = $objRout->urlFor('profile');
        $hrefHome = $objRout->urlFor('home');
        $hrefLogout = $objRout->urlFor('logout');
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
        <li><a href="${hrefLogout}">  <img src="${app_root}/html/img/logout.svg" width="32" height="32" alt="Mon Profil"> </a></li>
      </ul>
    </nav>
    </div>
EOT;
        return $html;
    }

    private function renderHeaderBack()
    {
        $html = "";
        $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $objRout = new \mf\router\Router();
        $hrefRetour = $objRout->urlFor('home');
        $html .= <<<EOT
        <nav>
          <a href="${hrefRetour}" class="back">
            <img src="${app_root}/html/img/back.svg" width="32" height="32" alt="fleche de retour">
          </a>
        </nav>
EOT;
        return $html;
    }

    private function renderFooter()
    {
        return 'La super app créée en Licence Pro &copy;2019';
    }


    private function renderHome()//nav bar
    {
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
      switch($dispo){
        case 0:
          $dispo = "indisponible";
          break;
        case 1:
          $dispo = "disponible";
          break;
        case 2:
          $dispo = "indisponible";
          break;
      }
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

    public function renderUserView(){
      $route = new \mf\router\Router();
      $http = new \mf\utils\HttpRequest();
      $hrefRetour = $route->urlFor('home');
      $url = $route->urlFor('profile', ['action' => 'modify']);
      $urlB = $route->urlFor('modify');
      $userData = $this->data;
      $user_name = $userData["username"];
      $name = $userData["name"];
      $surname = $userData["surname"];
      $mail = $userData["mail"];
      $adress = $userData["adress"];
      $postalcode = $userData["postalcode"];
      $city = $userData["city"];
      $picture = $userData["picture"];
      $name_sur = $surname. " " .$name;
      $city_post = $postalcode. " " .$city;
      if (isset($http->get['action']) && $http->get['action'] === 'modify'){
        $html = <<<EOT
        <div id="profil">
          <form class="conntect" method="post" action="{$urlB}" enctype="multipart/form-data">
            <div id="image">
             <div>
                <img src="${picture}" width="64" height="64" alt="photo de l'utilisateur">
              </div>
              <label for="file" class="label-file">parcourir...</label>
              <input id="file" type="file" name="fileToUpload" accept="image/png, image/gif, image/jpeg" id="fileToUpload">
            </div>
            <div class="infos">
              <input id="name" name="name" value="${surname}">
              <input id="surname" name="surname" value="${name}">
              <input id="user_name" name="user_name" value="${user_name}">
              <input id="mail" name="mail" value="${mail}">
              <div id="where">
                <input id="adress" name="adress" value="${adress}">
                <input id="postalcode" name="postalcode" value="${postalcode}">
                <input id="city"name="city" value="${city}">
              </div>
            </div>
            <input class="button-empty" type="submit" value="valider">
          </form>
        </div>
EOT;
      } else {
      $html = <<<EOT
      <div id="profil">
        <form class="conntect">
          <div id="image">
            <img src="${picture}" width="64" height="64" alt="photo de l'utilisateur">
          </div>
          <div class="infos">
            <p>${name_sur}</p>
            <p>${user_name}</p>
            <p>${mail}</p>
            <div id="where">
            <p>${adress}</p>
            <p>${city_post}</p>
            </div>
          </div>
          <a href="${url}"><img src=""><input class="button-empty" type="button" value="modifier"></a>
        </form>
      </div>
EOT;
    }

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

    private function renderBorrow()
    {
      setlocale (LC_TIME, 'fr_FR.utf8','fra');
      $objRout = new \mf\router\Router();
      $hrefRetour = $objRout->urlFor('home');
      $user = $this->data;
      $borrows = $user->borrows()->get();
      $app_root = (new \mf\utils\HttpRequest())->root;//Pour aller chercher les images
        $html = <<<EOT
          <main id="my_borrows">
          <h1>Mes emprunts</h1>
            <div class="container">
EOT;
        foreach ($borrows as $borrow) {
          $mediaBorrow = $borrow->media()->first();
            $title = $mediaBorrow->title;
            $picture = "data:image/jpeg;base64,".base64_encode($mediaBorrow->picture);
            $type = $mediaBorrow->type;
            $dateEmprunt = strftime("%A %d %B %G", strtotime($borrow->borrow_date_start));
            $dateRetour = strftime("%A %d %B %G", strtotime($borrow->borrow_date_end));
            $html .= <<<EOT
            <div class="item">
              <div class="item__info">
                <h3>${title}</h3>
                <p class="borrow">emprunté le : ${dateEmprunt}</p>
                <p class="return">A retourner le : ${dateRetour}</p>
              </div>
              <div class="item__img">
                <img src="${picture}" alt="${type}">
              </div>
            </div>
EOT;
        }
        return $html;
    }

  private function renderLogin(){
    $obj = new \mf\router\Router();
    $hrefSend = $obj->urlFor('checklogin');
    $error = '';
    if(null !== $this->data){
      $error = "<p>".$this->data->getMessage()."</p>";
    }
    $html = "";
      $html.= <<<EOT
      <form action="${hrefSend}" method="post" class="connect">
          <input type="email" name="mail" id="mail" required placeholder="Mail">
          <input type="password" name="password" id="password" required placeholder="Mot de passe">
          ${error}
          <button type="submit" name="button" class="button">Envoyer</button>
      </form>
EOT;
        return $html;
    }

    private function renderRegister()
      {
        $obj = new \mf\router\Router();
        $hrefSend = $obj->urlFor('checkregister');
        $html = "";
          $html.= <<<EOT
          <main id="sign_in">
            <form action="${hrefSend}" method="post" class="sign_in">
                <input type="text" name="mail" id="mail" required placeholder="Mail">
                <input type="text" name="name" id="name" required placeholder="Prenom">
                <input type="text" name="surname" id="surname" required placeholder="Nom">
                <input type="text" name="username" id="username" required placeholder="Nom d'utilisateur">
                <input type="text" name="address" id="address" required placeholder="Adresse">
                <input type="text" name="postalcode" id="postalcode" required placeholder="Code postal">
                <input type="text" name="city" id="city" required placeholder="Ville">
                <input type="tel" name="phone" id="phone" required placeholder="Tel">
                <input type="text" name="password" id="password" required placeholder="Mot de passe">
                <button type="submit" name="button" class="button">Envoyer</button>
            </form>
          </main>
EOT;
          return $html;
        }


    protected function renderBody($selector)
    {
        $content = "";
        $navBar = "";
        switch ($selector) {
            case 'home':
                $navBar = $this->renderHeader();
                $content = $this->renderHome();
                break;
            case 'detailMedia':
                $navBar = $this->renderHeaderBack();
                $content = $this->renderMedia();
                break;
            case 'profile':
                $navBar = $this->renderHeaderBack();
                $content = $this->renderUserView();
                break;
            case 'login':
                $content = $this->renderLogin();
                break;
            case 'borrow':
                $navBar = $this->renderHeaderBack();
                $content = $this->renderBorrow();
                break;
            case 'register':
                $content = $this->renderRegister();
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
