<?php

namespace App\Controllers\Process;

class Tokenizer 
{

    private static $agent;

    private static $timestamp;

    public function __construct()
    {
        
    }

    /**
     * generate token
     * param{$userdata}
     * return token
     */

   public static function generate($userdata)
   {
       self::$agent = 'dt-erp';
       self::$timestamp = date("Y-m-d");
       $agent = base64_encode(self::$agent);
       $timestamp = base64_encode(self::$timestamp);
       $payload = base64_encode(json_encode($userdata));
       return $agent . '.' . $timestamp . '.' . $payload;
   }

   /**
    * get logged user if not expired
    * param{$token}
    * return user payload or integer 0 or integer 1
    */

 public static function validate($token)
 {
    self::$agent = 'dt-erp';
    self::$timestamp = date("Y-m-d");
    $tkn = explode(".",$token);
    $expired = strtotime(self::$timestamp) - (24 * 60 * 60);
    $timestamp = strtotime(base64_decode($tkn[1]));
    $agent = base64_decode($tkn[0]);
    if($timestamp <= $expired){
        return 0; //expired
    } else if($agent != self::$agent) {
        return 1; //invalid request
    } else {
        return json_decode(base64_decode($tkn[2]),true);
    } 
 }

 /**
  * generate hash password
  * param{$password}
  * return hash
  */

  public static function generatePasswordHash($password)
  {
      return password_hash($password,PASSWORD_BCRYPT);
  }

  /**
   * validate hash password
   * param{$password}
   * param{$hash}
   * return boolean
   */

   public static function validatePasswordHash($password,$hash)
    {
        return password_verify($password,$hash);
    }


}

