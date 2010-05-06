<?php
/**
* SimpleXMLElement wrapper providing some additional functionality.
* 
* @author	Oliver Gondža
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	3rdParty
*/

/**
* Simple XmlElement extension.
*
* @class	NotSoSimpleXmlElement
* @package	3rdParty
* @author	Oliver Gondža
*/
class NotSoSimpleXmlElement extends SimpleXMLElement {

  /**
  * Obtain all children of an elements.
  *
  * @return	Array	Array of SimpleXMLElement
  */
  public function getChildren () {
  
    $followers = Array ();
    foreach ( $this->children () as $child ) {
      $followers[] = $child;
    }
    
    return $followers;
  }
  
  /**
  * Get array of node attributes.
  *
  * @return	Array	Array of SimpleXMLElement
  */
  public function getAttributes () {
  
    $attrs = Array ();
    foreach ( $this->attributes () as $attr => $value ) {
      $attrs[ $attr ] = $value;
    }
    
    return $attrs;
  }
}
