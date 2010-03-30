<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Attr extends MapFilter_Pattern_Node_Abstract {

  /**
  * @attribute: String
  * @valueFilter: String
  * @default: String
  */
  public function __construct (
      $attribute,
      $valueFilter = NULL,
      $default = NULL
  ) {
  
    $this->attribute = (String) $attribute;
    $this->valueFilter = $valueFilter;
    $this->default = (String) $default;
    
    return;
  }

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {
  
    $present = self::attrPresent (
        $this->attribute,
        $query
    );
    
    if ( $present ) {
      return $this->satisfied = TRUE;
    }
    
    if ( $this->default ) {
      
      $query[ $this->attribute ] = $this->default;

      return $this->satisfied = TRUE;
    }
    
    return $this->satisfied = FALSE;
  }
  
  /**
  * Determine whether a node has an attribute
  * return: Bool
  */
  public static function hasAttr () {
  
    return TRUE;
  }
  
  public static function hasFollowers () {
  
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
