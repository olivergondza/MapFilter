<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_All extends MapFilter_Pattern_Node_Abstract {

  /**
  * Node Followers
  * @var: Array ( MapFilter_Pattern_Node_Interface )
  */
  public $content = Array ();

  /**
  * Fluent Method; Set content
  * @content: Array
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
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
  public function hasAttr () {
  
    return FALSE;
  }
  
  public function hasFollowers () {
  
    return TRUE;
  }
}
