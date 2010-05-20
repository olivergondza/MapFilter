<?php
/**
* All Pattern node 
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.4
*/

/**
* @file		MapFilter/Pattern/Tree/Node.php
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
* MapFilter pattern tree all node
*
* @class	MapFilter_Pattern_Tree_Node_All
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.4
*/
final class MapFilter_Pattern_Tree_Node_All
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * @copybrief		MapFilter_Pattern_Tree_Interface::satisfy
  *
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  *
  * @copydetails	MapFilter_Pattern_Tree_Interface::satisfy
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
