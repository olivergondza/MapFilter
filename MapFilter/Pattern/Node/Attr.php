<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Attr extends MapFilter_Pattern_Node_Abstract {

  /**
  * Node attribute
  * @var: String
  */
  public $attribute = "";
  
  /**
  * Attr default value
  * @var: String
  */
  public $default = NULL;
  
  /**
  * Attr value Pattern
  * @var: String; REGEX
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
    
        return $this->setSatisfied ( TRUE, $asserts );
      }
    }
    
    /** Set default if defined */
    if ( $this->default !== NULL ) {
      
      $query[ $this->attribute ] = $this->default;

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
  
  public function hasFollowers () {
  
    return FALSE;
  }
  
  /** All nodes must clone */
  public function __clone () {
  
    return;
  }
  
  /**
  * Cast to string
  * @return: String
  */
  public function __toString () {
  
    return $this->attribute;
  }
}
