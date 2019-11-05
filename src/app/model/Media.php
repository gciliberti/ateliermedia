<?php
namespace app\model;

class Media extends \Illuminate\Database\Eloquent\Model{
  protected $table ='media';
  protected $primaryKey = 'id';
  public $timestamps = false;


  /*public function liked(){
    return $this->belongsToMany("tweeterapp\model\Tweet","like","user_id","tweet_id");
  }

  public function followedBy(){
    return $this->belongsToMany("tweeterapp\model\User","follow","followee","follower");
  }

  public function follows(){
    return $this->belongsToMany("tweeterapp\model\User","follow","follower","followee");
  }*/




}
 ?>
