<?php
/**
* Some Pattern node
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
final class MapFilter_Pattern_Tree_Node_Some
    extends MapFilter_Pattern_Tree_Node
{

  /**
  * Compare strictly
  * @var Bool
  */
  const STRICT_COMPARISON = TRUE;

  /**
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {

    $satisfiedFollowers = Array ();
    foreach ( $this->getContent () as $follower ) {

      $satisfiedFollowers[] = $follower->satisfy ( $param );
    }
    
    $satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        self::STRICT_COMPARISON
    );
    
    return $this->setSatisfied ( $satisfied, $param );
  }
}
