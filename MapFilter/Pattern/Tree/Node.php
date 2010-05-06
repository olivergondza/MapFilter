<?php
/**
* Ancestor of pattern tree nodes.
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @since	0.3
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
* Abstract class for pattern tree node,
*
* @class	MapFilter_Pattern_Tree_Node
* @package	MapFilter
* @since	0.3
*/
abstract class MapFilter_Pattern_Tree_Node
    extends MapFilter_Pattern_Tree
    implements MapFilter_Pattern_Tree_Node_Interface
{

  /**
  * Node Followers
  *
  * @since	0.3
  *
  * @var	Array	$content
  */
  private $content = Array ();

  /**
  * Fluent Method; Set content
  *
  * @since	0.3
  *
  * @param	content		A content to set
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
  * Get node followers
  *
  * @since	0.3
  *
  * @return	Array	Node content reference
  */
  public function &getContent () {
  
    return $this->content;
  }
  
  /**
  * PickUp Nodes
  *
  * @since	0.3
  *
  * @param	param	MapFilter_Pattern_PickUpParam to obtain results
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {
      return;
    }
  
    foreach ( $this->getContent () as $follower ) {

      $follower->pickUp ( $param );
    }
  }
  
  /**
  * Clone node followers
  *
  * @since	0.3
  */
  public function __clone () {
  
    $content = $this->getContent ();
    foreach ( $content as &$follower ) {
    
      $follower = clone ( $follower );
    }
  }
}
