<?php
/**
* KeyAttr Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_KeyAttr extends MapFilter_Pattern_Node_Abstract {

  /**
  * @followers: Array ( MapFilter_Pattern_Node_Abstract )
  * @valueFilter: String
  */
  public function __construct (
      Array $followers,
      $attribute,
      $valueFilter = NULL
  ) {
  
    $this->content = $followers;
    $this->attribute = (String) $attribute;
    $this->valueFilter = $valueFilter;
    
    return;
  }

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {

    $attrName = $this->attribute;

    $attrExists = self::attrPresent ( $attrName, $query );

    if ( !$attrExists ) {

      return $this->satisfied = FALSE;
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $this->content as $follower ) {
      
      $fits = $follower->valueFits (
          $query[ $attrName ]
      );
      
      if ( $fits ) {

        return $this->satisfied = $follower->satisfy ( $query );
      } 
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
  
    return TRUE;
  }
  
  /**
  * Cast to string
  * @return: String
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
