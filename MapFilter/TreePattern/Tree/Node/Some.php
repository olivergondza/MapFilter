<?php
/**
 * Some Pattern node
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */

/**
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
 * MapFilter pattern tree some node
 *
 * @class       MapFilter_TreePattern_Tree_Node_Some
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */
final class MapFilter_TreePattern_Tree_Node_Some extends
    MapFilter_TreePattern_Tree_Node
{

  /**
   * @copybrief         MapFilter_TreePattern_Tree_Interface::satisfy
   *
   * Satisfy the node when there is at least one satisfied follower.  Thus
   * satisfy MUST be mapped on ALL followers.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy
   */
  public function satisfy ( &$query, Array &$asserts ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->getContent () as $follower ) {

      $satisfiedFollowers[] = $follower->satisfy ( $query, $asserts );
    }
    
    $this->satisfied = in_array (
        TRUE,
        $satisfiedFollowers
    );
    
    if ( $this->satisfied ) {
      
      $this->setAssertValue ( $asserts );
    }
    
    return $this->satisfied;
  }
}
