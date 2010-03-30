<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_One extends MapFilter_Pattern_Node_Abstract {

  /**
  * @followers: Array ( MapFilter_Pattern_Node_Abstract )
  * @valueFilter: String
  */
  public function __construct (
      Array $followers = Array (),
      $valueFilter = NULL
  ) {
  
    $this->content = $followers;
    $this->valueFilter = $valueFilter;
    
    return;
  }

  /**
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @query: Array
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {

    foreach ( $this->content as $follower ) {
      
      if ( $follower->satisfy ( $query ) ) {

        return $this->satisfied = TRUE;
      }
    }
    
    return $this->satisfied = FALSE;
  }
  
  /**
  * Determine whether a node has an attribute
  * return: Bool
  */
  public static function hasAttr () {
  
    return FALSE;
  }
  
  public static function hasFollowers () {
  
    return TRUE;
  }
}
