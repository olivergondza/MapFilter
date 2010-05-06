<?php
/**
* Opt Pattern node
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @since	0.3
*/

/**
* @file		MapFilter/Pattern/Tree/Node.php
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
* MapFilter pattern tree opt node
*
* @class	MapFilter_Pattern_Tree_Node_Opt
* @package	MapFilter
* @since	0.3
*/
final class MapFilter_Pattern_Tree_Node_Opt
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * @copybrief		MapFilter_Pattern_Tree_Interface::satisfy
  *
  * That node is always satisfied.
  * Thus satisfy MUST be mapped on ALL followers.
  *
  * @copydetails	MapFilter_Pattern_Tree_Interface::satisfy
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $param );
    }

    return $this->setSatisfied ( TRUE, $param );
  }
}
