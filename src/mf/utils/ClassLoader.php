<?php
namespace mf\utils;
class ClassLoader{
private $prefix;

  public function __construct($path)
  {
    $this->prefix = $path;
  }

  private function loadClass($chemin){
    $sortie = str_replace("\\",DIRECTORY_SEPARATOR,$chemin);

    $final = $this->prefix.DIRECTORY_SEPARATOR.$sortie.".php";
    if(file_exists($final))
    {
      
      require($final);
    }
  }

  public function register(){
    spl_autoload_register(array($this, 'loadClass'));
  }
}
/*
$test = new ClassLoader("src\test\sdfsdf");
$test -> register();*/
