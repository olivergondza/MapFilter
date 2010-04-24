<?php
/**
* SimpleXMLElement wrapper providing some additional functionality
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
class NotSoSimpleXMLElement extends SimpleXMLElement {

  /**
  * Obtain all children of an elements
  * @return Array ( SimpleXMLElement )
  */
  public function getChildren () {
  
    $followers = Array ();
    foreach ( $this->children () as $child ) {
      $followers[] = $child;
    }
    
    return $followers;
  }
  
  /**
  * Get array of node attributes
  * @return Array ( SimpleXMLElement )
  */
  public function getAttributes () {
  
    $attrs = Array ();
    foreach ( $this->attributes () as $attr => $value ) {
      $attrs[ $attr ] = $value;
    }
    
    return $attrs;
  }
}