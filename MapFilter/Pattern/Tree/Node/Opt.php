<?php
/**
* Opt Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../Node_Abstract.php' );

final class MapFilter_Pattern_Tree_Node_Opt
    extends MapFilter_Pattern_Tree_Node_Abstract
{

  /**
  * That node is always satisfied.
  * Thus satisfy MUST be mapped on ALL followers.
  * @param: MapFilter_Pattern_SatisfyParam
  * @return: Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $param );
    }

    return $this->setSatisfied ( TRUE, $param->asserts );
  }
}
