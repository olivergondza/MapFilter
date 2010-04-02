<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Some extends MapFilter_Pattern_Node_Abstract {

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
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->content as $follower) {

      $satisfiedFollowers[] = $follower->satisfy ( $query, $asserts );
    }
    
    $satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        TRUE /** Compare strictly */
    );
    
    return $this->setSatisfied ( $satisfied, $asserts );
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
