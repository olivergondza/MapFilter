<?php
/**
* All Pattern node 
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

final class MapFilter_Pattern_Tree_Node_All
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    foreach ( $this->getContent () as $follower ) {
      
      if ( !$follower->satisfy ( $param ) ) {

        return $this->setSatisfied ( FALSE, $param );
      }
    }
    
    return $this->setSatisfied ( TRUE, $param );
  }
}
