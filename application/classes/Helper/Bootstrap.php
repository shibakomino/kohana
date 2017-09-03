<?php
include SYSPATH.'classes/Helper/Bootstrap.php';

/**
 * Created by PhpStorm.
 * User: colinleung
 * Date: 1/9/2017
 * Time: 11:39 AM
 */

class Helper_Bootstrap extends Kohana\Helper\Bootstrap{

  private static function redirect_insecure(){


    if(!empty($_POST))return FALSE;
    if(!isset(Kohana::$config))return FALSE;
    $ssl_enable = Kohana::$config->load('site.ssl_enable');
    $current_protocol = self::get_protocol();

    if($ssl_enable === TRUE && $current_protocol == 'http'){
      if(preg_match('/^local./i', $_SERVER['HTTP_HOST'])==1)return FALSE;

      $url = "https://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

      //CHECK: why trim the query string.
//      if(!empty($_SERVER['QUERY_STRING'])){
//        $url = str_replace('?'.$_SERVER['QUERY_STRING'], '', $url);
//      }

      header('Location: '.$url);
      return TRUE;
    }

    return FALSE;
  }

  public static function executeRequest(){
    if(Helper_Bootstrap::redirect_insecure() == TRUE)return '<!-- redirect SSL -->';

    return Kohana\Helper\Bootstrap::executeRequest();
  }
}
