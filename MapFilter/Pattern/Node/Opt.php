<?php
/**
* Opt Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Opt extends MapFilter_Pattern_Node_Abstract {

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
  * That node is always satisfyied.
  * Thus satisfy MUST be mapped on ALL followers.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {
  
    foreach ( $this->content as $follower ) {
    
      $follower->satisfy ( $query );
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
