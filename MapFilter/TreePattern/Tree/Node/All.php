<?php
/**
 * All Pattern node 
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

/**
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
 * MapFilter pattern tree all node
 *
 * @class       MapFilter_TreePattern_Tree_Node_All
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
final class MapFilter_TreePattern_Tree_Node_All extends
    MapFilter_TreePattern_Tree_Node
{

  /**
   * @copybrief 	MapFilter_TreePattern_Tree_Interface::satisfy
   *
   * Satisfy the node just if there are no unsatisfied follower.  Finding
   * unsatisfied follower may stop mapping since there is no way to satisfy
   * parent by any further potentially satisfied follower.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    foreach ( $this->getContent () as $follower ) {
      
      if ( !$follower->satisfy ( $query, $asserts ) ) {

        $this->setAssertValue ( $asserts );
        return $this->satisfied = FALSE;
      }
    }
    
    return $this->satisfied = TRUE;
  }
}
