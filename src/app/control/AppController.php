<?php

namespace app\control;

class AppController extends \mf\control\AbstractController {
  public function __construct(){
    parent::__construct();
  }

  public function viewHome(){
    $medias = \app\model\Media::select()->get();
    $vue = new \app\view\AppView($medias);
    $vue->render("home");
  }

  public function viewMedia(){
      $http = new \mf\utils\HttpRequest();
      $media = \app\model\Media::where('id', '=', $http->get['id'])->get();
      $vue = new \app\view\AppView($media);
      $vue->render('detailMedia');
  }

  public function viewBorrow(){
      $mailUser = $_SESSION['user_login'];
      $user = \app\model\User::where('mail', '=', $mailUser)->first();
      $vue = new \app\view\AppView($user);
      $vue->render('borrow');
  }
}
