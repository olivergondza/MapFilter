<?php
/**
* One Pattern node
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
* MapFilter pattern tree one node
*
* @class	MapFilter_Pattern_Tree_Node_One
* @package	MapFilter
* @since	0.3
*/
final class MapFilter_Pattern_Tree_Node_One
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * @copybrief		MapFilter_Pattern_Tree_Interface::satisfy
  *
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  *
  * @copydetails	MapFilter_Pattern_Tree_Interface::satisfy
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {

    foreach ( $this->getContent () as $follower ) {
      
      if ( $follower->satisfy ( $param ) ) {

        return $this->setSatisfied ( TRUE, $param );
      }
    }
    
    return $this->setSatisfied ( FALSE, $param );
  }
}
