<?php
namespace app\model;

class User extends \Illuminate\Database\Eloquent\Model{
  protected $table ='user';
  protected $primaryKey = 'id';
  public $timestamps = false;

  public function borrows(){
    return $this->hasMany('app\model\Borrow','id_user');
  }
}
 ?>
