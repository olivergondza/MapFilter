<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_All extends MapFilter_Pattern_Node_Abstract {

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
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {
  
    foreach ( $this->content as $follower ) {
      
      if ( !$follower->satisfy ( $query ) ) {

        return $this->satisfied = FALSE;
      }
    }
    
    return $this->satisfied = TRUE;
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
