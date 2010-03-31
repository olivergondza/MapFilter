<?php
/**
* Opt Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_Opt extends MapFilter_Pattern_Node_Abstract {

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
  public function hasAttr () {
  
    return FALSE;
  }
  
  public function hasFollowers () {
  
    return TRUE;
  }
}
