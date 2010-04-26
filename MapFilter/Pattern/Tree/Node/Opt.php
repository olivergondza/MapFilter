<?php
/**
* Opt Pattern node
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include abstract class
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
* @package MapFilter
*/
final class MapFilter_Pattern_Tree_Node_Opt
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * That node is always satisfied.
  * Thus satisfy MUST be mapped on ALL followers.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $param );
    }

    return $this->setSatisfied ( TRUE, $param );
  }
}
