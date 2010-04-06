<?php
/**
* One Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Policy.php' );

final class MapFilter_Pattern_Node_One extends MapFilter_Pattern_Node_Policy {

  /**
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @&query: Array
  * @&asserts: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    foreach ( $this->getContent () as $follower ) {
      
      if ( $follower->satisfy ( $query, $asserts ) ) {

        return $this->setSatisfied ( TRUE, $asserts );
      }
    }
    
    return $this->setSatisfied ( FALSE, $asserts );
  }
}
