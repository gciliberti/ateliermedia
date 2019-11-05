<?php

namespace mf\router;



class Router extends AbstractRouter{

  public function __construct(){
      parent::__construct();
  }

  public function addRoute($name, $url, $ctrl, $mth, $lvl){
     self::$routes += array($url=>array($ctrl, $mth,$lvl));
     self::$aliases += array($name=>$url);
  }

  public function setDefaultRoute($url){
     self::$aliases += array('default'=>$url);
  }

  public function run(){
     $path = $this->http_req->path_info;

     $pathDefault = self::$aliases['default'];
     $ctrl = self::$routes[$pathDefault][0];
     $mth = self::$routes[$pathDefault][1];

     $accesstest = new \mf\auth\Authentification();
     if(isset(self::$routes[$path]) && $accesstest->checkAccessRight(self::$routes[$path][2]))
     {
       $ctrl = self::$routes[$path][0];
       $mth = self::$routes[$path][1];
     }

     $c = new $ctrl();
     $c->$mth();

  }

  public static function executeRoute($alias){
    $path = self::$aliases[$alias];
    $ctrl = self::$routes[$path][0];
    $mth = self::$routes[$path][1];

    $c = new $ctrl();
    $c->$mth();
  }

  public function urlFor($route_name, $param_list=[]){
    /*
     * Méthode urlFor : retourne l'URL d'une route depuis son alias
     *
     * Paramètres :
     *
     * - $route_name (String) : alias de la route
     * - $param_list (Array) optionnel : la liste des paramètres si l'URL prend
     *          de paramètre GET. Chaque paramètre est représenté sous la forme
     *          d'un tableau avec 2 entrées : le nom du paramètre et sa valeur
     *
     * Algorthme:
     *
     * - Depuis le nom du scripte et l'URL stocké dans self::$routes construire
     *   l'URL complète
     * - Si $param_list n'est pas vide
     *      - Ajouter les paramètres GET a l'URL complète
     * - retourner l'URL
     *
     */
     $racine = $this->http_req->script_name;
     $page = self::$aliases[$route_name];
     $Url = $racine.$page;

     if(!empty($param_list)){
       $Url.="?";
       $i=0;
       foreach ($param_list as $key => $value) {
         $Url.=$key;
         $Url.="=";
         $Url.=$value;
         $i++;
         if($i>0 && $i<count($param_list)){
           $Url.="&";
         }
       }
   }
     return $Url;
  }
}
