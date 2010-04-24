<?php
/**
* Some Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

final class MapFilter_Pattern_Tree_Node_Some
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * Compare strictly
  * @var Bool
  */
  const STRICT_COMPARISON = TRUE;

  /**
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->getContent () as $follower ) {

      $satisfiedFollowers[] = $follower->satisfy ( $param );
    }
    
    $satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        self::STRICT_COMPARISON
    );
    
    return $this->setSatisfied ( $satisfied, $param );
  }
}
