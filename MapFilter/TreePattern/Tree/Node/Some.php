<?php
/**
* Some Pattern node
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/

/**
* @file		MapFilter/TreePattern/Tree/Node.php
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
* MapFilter pattern tree some node
*
* @class	MapFilter_TreePattern_Tree_Node_Some
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/
final class MapFilter_TreePattern_Tree_Node_Some
    extends MapFilter_TreePattern_Tree_Node
{

  /**
  * @copybrief		MapFilter_TreePattern_Tree_Interface::satisfy
  *
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  *
  * @copydetails	MapFilter_TreePattern_Tree_Interface::satisfy
  */
  public function satisfy ( MapFilter_TreePattern_SatisfyParam $param ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->getContent () as $follower ) {

      $satisfiedFollowers[] = $follower->satisfy ( $param );
    }
    
    $satisfied = in_array (
        TRUE,
        $satisfiedFollowers
    );
    
    return $this->setSatisfied ( $satisfied, $param );
  }
}
