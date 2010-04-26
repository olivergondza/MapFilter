<?php
/**
* One Pattern node
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
final class MapFilter_Pattern_Tree_Node_One
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
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
