<?php
/**
* Attr Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

final class MapFilter_Pattern_Node_Attr extends
    MapFilter_Pattern_Node_Abstract
{

  /**
  * Node attribute
  * @var: String
  */
  public $attribute = "";
  
  /**
  * Attribute value
  * @var: String
  */
  public $value = "";
  
  /**
  * Attr default value
  * @var: String
  */
  public $default = NULL;
  
  /**
  * Attr value Pattern
  * @var: String; REGEXP
  */
  public $valuePattern = NULL;
  
  /**
  * Fluent Method; Set attribute
  * @attribute: String
  */
  public function setAttribute ( $attribute ) {

    $this->attribute = $attribute;
    return $this;
  }
  
  /**
  * Fluent Method; Set default
  * @default: String
  */
  public function setDefault ( $default ) {

    $this->default = $default;
    return $this;
  }

  /**
  * Fluent Method; Set valuePattern
  * @valuePattern: String
  */
  public function setValuePattern ( $valuePattern ) {

    $this->valuePattern = $valuePattern;
    return $this;
  }

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @&query: Array
  * @&assert: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {
  
    /** If argument exists */
    $present = self::attrPresent (
        $this->attribute,
        $query
    );
    
    if ( $present ) {
    
      /** And matches pattern */
      $fitsPattern = self::valueFits (
          $query[ $this->attribute ],
          $this->valuePattern
      );

      if ( $fitsPattern ) {
    
        $this->value = $query[ $this->attribute ];
    
        return $this->setSatisfied ( TRUE, $asserts );
      }
    }
    
    /** Set default if defined */
    if ( $this->default !== NULL ) {
      
      $this->value = $this->default;

      return $this->setSatisfied ( TRUE, $asserts );
    }
    
    return $this->setSatisfied ( FALSE, $asserts );
  }
  
  /**
  * Determine whether a node has an attribute
  * return: Bool
  */
  public function hasAttr () {
  
    return TRUE;
  }
  
  /**
  * Determine whether a node has some followers
  * return: Bool
  */
  public function hasFollowers () {
  
    return FALSE;
  }
  
  /**
  * Attr node has nothing to clone
  */
  public function __clone () {
  
    return;
  }
  
  /**
  * Cast to string
  * @return: String
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
