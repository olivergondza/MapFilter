<?php
/**
* SimpleXmlElement wrapper providing some additional functionality
* 
* @author Oliver Gondža
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
*/

/**
* Simple XmlElement extension
*/
class NotSoSimpleXmlElement extends SimpleXMLElement {

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