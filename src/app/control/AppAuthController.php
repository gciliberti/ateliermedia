<?php

namespace app\control ;

Class AppAuthController extends \mf\control\AbstractController {
  public function login($e=null){
    if(isset($_SESSION['access_level'])){
      \mf\router\Router::executeRoute('home');
    }
    else{
    $vue = new \app\view\AppView($e);
    $vue->render("login");
  }

  }

  public function checkLogin(){
    //Recup données form
    $httpReq = new \mf\utils\HttpRequest;
    if(isset($httpReq->post['mail']) && isset($httpReq->post['password'])){
      $mail = filter_var($httpReq->post["mail"],FILTER_SANITIZE_STRING);
      $password = filter_var($httpReq->post["password"],FILTER_SANITIZE_STRING);
      $auth = new \app\auth\AppAuthentification();
      try{

        $auth->loginUser($mail,$password);
        \mf\router\Router::executeRoute('home');

      }
      catch(\mf\auth\exception\AuthentificationException $e){
        $this->login($e);

      }

    }

  }

  public function register(){
    $vue = new \app\view\AppView();
    $vue->render("register");
  }

  public function checkRegister(){
    $httpReq = new \mf\utils\HttpRequest;
    if(isset($httpReq->post['mail'])
    && isset($httpReq->post['name'])
    && isset($httpReq->post['surname'])
    && isset($httpReq->post['username'])
    && isset($httpReq->post['address'])
    && isset($httpReq->post['postalcode'])
    && isset($httpReq->post['city'])
    && isset($httpReq->post['phone'])
    && isset($httpReq->post['password'])){

      $mail = filter_var($httpReq->post["mail"],FILTER_SANITIZE_EMAIL);
      $name = filter_var($httpReq->post["name"],FILTER_SANITIZE_STRING);
      $surname = filter_var($httpReq->post["surname"],FILTER_SANITIZE_STRING);
      $username = filter_var($httpReq->post["username"],FILTER_SANITIZE_STRING);
      $address = filter_var($httpReq->post["address"],FILTER_SANITIZE_STRING);
      $postalcode = filter_var($httpReq->post["postalcode"],FILTER_SANITIZE_STRING);
      $city = filter_var($httpReq->post["city"],FILTER_SANITIZE_STRING);
      $phone = filter_var($httpReq->post["phone"],FILTER_SANITIZE_STRING);
      $password = filter_var($httpReq->post["password"],FILTER_SANITIZE_STRING);

      $auth = new \app\auth\AppAuthentification();
      try{

        $auth->createUser($mail,$name,$surname,$username,$address,$postalcode,$city,$phone, $password);
        \mf\router\Router::executeRoute('login');

      }
      catch(\mf\auth\exception\AuthentificationException $e){
        $this->register();
      }
    }
  }

  public function logout(){
    $auth = new \app\auth\AppAuthentification();
    $auth->logout();
    \mf\router\Router::executeRoute('login');
  }
}
