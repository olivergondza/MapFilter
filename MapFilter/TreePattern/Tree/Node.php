<?php
/**
* Ancestor of pattern tree nodes.
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
* @file		MapFilter/TreePattern/Tree.php
*/
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
* @file		MapFilter/TreePattern/Tree/Node/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/Node/Interface.php' );

/**
* Abstract class for pattern tree node,
*
* @class	MapFilter_TreePattern_Tree_Node
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/
abstract class MapFilter_TreePattern_Tree_Node
extends MapFilter_TreePattern_Tree
implements
    MapFilter_TreePattern_Tree_Node_Interface
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
  * @param	param	MapFilter_TreePattern_PickUpParam to obtain results
  */
  public function pickUp ( MapFilter_TreePattern_PickUpParam $param ) {

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
