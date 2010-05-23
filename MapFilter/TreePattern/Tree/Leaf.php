<?php
/**
* Pattern Leaf
*
* Ancestor of Leaves
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.4
*/

/**
* @file		MapFilter/TreePattern/Tree.php
*/
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
* @file		MapFilter/TreePattern/Tree/Node/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/Node/Interface.php' );

/**
* Abstract class for pattern tree leaf
*
* @class	MapFilter_TreePattern_Tree_Leaf
* @ingroup	gtreepattern
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.4
*/
abstract class MapFilter_TreePattern_Tree_Leaf
    extends MapFilter_TreePattern_Tree
{

  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::__clone()}
  * Overwrite MapFilter_Pattern_Tree deep cloning method
  */
  public function __clone () {}
}
