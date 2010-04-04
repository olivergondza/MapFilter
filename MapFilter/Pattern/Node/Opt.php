<?php
/**
* Opt Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Policy.php' );

final class MapFilter_Pattern_Node_Opt extends MapFilter_Pattern_Node_Policy {

  /**
  * That node is always satisfyied.
  * Thus satisfy MUST be mapped on ALL followers.
  * @&query: Array
  * @&query: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {
  
    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $query, $asserts );
    }

    return $this->setSatisfied ( TRUE, $asserts );
  }
}
