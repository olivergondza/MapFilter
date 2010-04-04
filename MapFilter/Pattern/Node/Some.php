<?php
/**
* Some Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Policy.php' );

final class MapFilter_Pattern_Node_Some extends MapFilter_Pattern_Node_Policy {

  /**
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @&query: Array
  * @&asserts: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->getContent () as $follower ) {

      $satisfiedFollowers[] = $follower->satisfy ( $query, $asserts );
    }
    
    $satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        TRUE /** Compare strictly */
    );
    
    return $this->setSatisfied ( $satisfied, $asserts );
  }
}
