<?php

namespace app\control ;

Class AppAuthController extends \mf\control\AbstractController {
  public function login(){
    $vue = new \app\view\AppView();
    $vue->render("login");

  }

  public function checkLogin(){
    //Recup données form
    $post = new \mf\utils\HttpRequest;
    if(isset($post)){
      $mail = $post->post["mail"];
      $password = $post->post["password"];
      $auth = new \app\auth\AppAuthentification();
      try{

        $auth->loginUser($mail,$password);
        \mf\router\Router::executeRoute('home');

      }
      catch(mf\auth\exception\AuthentificationException $e){
        $this->login();
      }

    }

  }

}
