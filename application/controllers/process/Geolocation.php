<?php

namespace App\Controllers\Process;

class Geolocation
{
   
    /**
     * static function for calling third party Geoloc API
     * @address the remote address
     * @return json data
     */
    private static function CallGeolocApi($address)
    {
         
          // Open connection

          $ch = curl_init();

          $url = 'http://ip-api.com/json/' . $address; 

          // Set the url, number of POST vars, POST data
  
          curl_setopt($ch, CURLOPT_URL, $url); 
  
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
          // Disabling SSL Certificate support temporarly
  
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
  
          // Execute post
  
          $json_data = curl_exec($ch); 
  
          curl_close($ch);
  
        return json_decode($json_data,true);
    }

    public static function Resolve()
    {
        $address = $_SERVER['REMOTE_ADDR'];
        $json = self::CallGeolocApi($address);
        return $json;
    }
}