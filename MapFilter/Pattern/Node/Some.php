<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Some extends MapFilter_Pattern_Node_Abstract {

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
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  public function satisfy ( Array &$query ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->content as $follower) {

      $satisfiedFollowers[] = $follower->satisfy ( $query );
    }
    
    return $this->satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        TRUE /** Compare strictly */
    );
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
