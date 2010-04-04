<?php
/**
* All Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Policy.php' );

final class MapFilter_Pattern_Node_All extends MapFilter_Pattern_Node_Policy {

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @&query: Array ( String )
  * @&asserts: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {
  
    foreach ( $this->getContent () as $follower ) {
      
      if ( !$follower->satisfy ( $query, $asserts ) ) {

        return $this->setSatisfied ( FALSE, $asserts );
      }
    }
    
    return $this->setSatisfied ( TRUE, $asserts );
  }
}
