<?php
namespace mf\auth;

Class Authentification extends AbstractAuthentification{
  function __construct(){
    if(isset($_SESSION['user_login'] )){
      $this->user_login = $_SESSION['user_login'] ;
      $this->access_level= $_SESSION['access_level'];
      $this->logged_in = true;
    }
    else{
      $this->user_login= null;
      $this->access_level = self::ACCESS_LEVEL_NONE;
      $this->logged_in = false;
    }
  }
  public function updateSession($mail, $level){
    $this->user_login = $mail;
    $this->access_level = $level;
    $_SESSION['user_login'] = $mail;
    $_SESSION['access_level'] = $level;
    $this->logged_in = true;

  }
  public function logout(){
    unset($_SESSION['user_login']);
    unset($_SESSION['access_level']);
    $this->user_login = null;
    $this->access_level = self::ACCESS_LEVEL_NONE;
    $this->logged_in = false;
  }

  public function checkAccessRight($requested){
      if($requested > $this->access_level){
        return false;
      }
      return true;
  }

  public function login($mail, $db_pass,$given_pass,$level){
    if(!$this->verifyPassword($given_pass,$db_pass)){
      throw new \mf\auth\exception\AuthentificationException('Les mdp ne correspondent pas');
    }
    else{
      $this->updateSession($mail,$level);

    }
  }

  public function hashPassword($password){
    $hash = password_hash($password,PASSWORD_DEFAULT);
    return $hash;
  }

  public function verifyPassword($password, $hash){
    return password_verify($password,$hash);
  }



}
