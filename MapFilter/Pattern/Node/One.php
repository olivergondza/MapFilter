<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_One extends MapFilter_Pattern_Node_Abstract {

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
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @query: Array
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    foreach ( $this->content as $follower ) {
      
      if ( $follower->satisfy ( $query, $asserts ) ) {

        return $this->setSatisfied ( TRUE, $asserts );
      }
    }
    
    return $this->setSatisfied ( FALSE, $asserts );
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
