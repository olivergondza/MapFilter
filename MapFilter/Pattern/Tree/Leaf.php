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
*/

/**
* @file		MapFilter/Pattern/Tree.php
*/
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
* @file		MapFilter/Pattern/Tree/Node/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/Node/Interface.php' );

/**
* Abstract class for pattern tree leaf
*
* @class	MapFilter_Pattern_Tree_Leaf
* @package	MapFilter
*/
abstract class MapFilter_Pattern_Tree_Leaf
    extends MapFilter_Pattern_Tree
{

  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::__clone()}
  */
  public function __clone () {
  
    return;
  }
}
