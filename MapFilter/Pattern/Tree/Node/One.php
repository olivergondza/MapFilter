<?php
/**
* One Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../Node_Abstract.php' );

final class MapFilter_Pattern_Tree_Node_One
    extends MapFilter_Pattern_Tree_Node_Abstract
{

  /**
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @param: MapFilter_Pattern_SatisfyParam
  * @return: Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {

    foreach ( $this->getContent () as $follower ) {
      
      if ( $follower->satisfy ( $param ) ) {

        return $this->setSatisfied ( TRUE, $param->asserts );
      }
    }
    
    return $this->setSatisfied ( FALSE, $param->asserts );
  }
}
