<?php 

 namespace GAPTheme;

 class DePro {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
 
  private function __construct() {
    //your code here
  }
 }