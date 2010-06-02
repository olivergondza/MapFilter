<?php
/**
* Opt Pattern node
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
* MapFilter pattern tree opt node
*
* @class	MapFilter_TreePattern_Tree_Node_Opt
* @ingroup	gtreepattern
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/
final class MapFilter_TreePattern_Tree_Node_Opt extends
    MapFilter_TreePattern_Tree_Node
{

  /**
  * @copybrief		MapFilter_TreePattern_Tree_Interface::satisfy
  *
  * That node is always satisfied.
  * Thus satisfy MUST be mapped on ALL followers.
  *
  * @copydetails	MapFilter_TreePattern_Tree_Interface::satisfy
  */
  public function satisfy ( &$query, Array &$asserts ) {
  
    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $query, $asserts );
    }

    return $this->setSatisfied ( TRUE, $asserts );
  }
}
