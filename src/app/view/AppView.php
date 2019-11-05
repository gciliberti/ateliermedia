<?php

namespace app\view;

use mf\router\Router;

class AppView extends \mf\view\AbstractView
{

    /* Constructeur
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct($data = null)
    {
        parent::__construct($data);
    }

    /* Méthode renderHeader
    *
    *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
    */
    private function renderHeader()
    {
        $html = "";
        $html .= <<<EOT
    <h1>Notre super app</h1>
EOT;
        return $html;
    }

    /* Méthode renderFooter
    *
    * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
    */
    private function renderFooter()
    {
        return 'La super app créée en Licence Pro &copy;2019';
    }


    private function renderHome()
    {//private
        $html = "";

        foreach ($this->data as $media) {
            $title = $media->title;
            $type = $media->type;
            $genre = $media->genre;
            $dispo = $media->disponibility;
            $picture = "data:image/jpeg;base64," . base64_encode($media->picture);

            $router = new \mf\router\Router();
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

    private function renderMedia()
    {
        $html = '';

        foreach ($this->data as $media) {
            $title = $media->title;
            $type = $media->type;
            $genre = $media->genre;
            $picture = "data:image/jpeg;base64," . base64_encode($media->picture);
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
        switch ($selector) {
            case 'home':
                $content = $this->renderHome();
                break;
            case 'detailMedia':
                $content = $this->renderMedia();
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
