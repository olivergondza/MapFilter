<?php
/**
* Abstract Parameter Object
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
abstract class MapFilter_Pattern_ParameterObject {

  /**
  * Since all possible Parameter Object values has to be declared,
  * behave assertively.
  */
  public function __set ( $name, $value ) {
  
    trigger_error (
        get_class ( $this ) . "has no value like '$name'",
        E_USER_ERROR
    );
  }
  
  public function __get ( $name ) {
  
    trigger_error (
        get_class ( $this ) . "has no value like '$name'",
        E_USER_ERROR
    );
  }
  
  public static function __callStatic ( $name, $arguments ) {
  
    trigger_error (
        get_class ( $this ) . "has no static method like '$name'",
        E_USER_ERROR
    );
  }
  
// UNIMPLEMENTED  
  public function __call ( $name, $arguments ) {
  
  }
}