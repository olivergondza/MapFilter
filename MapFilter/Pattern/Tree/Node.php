<?php
/**
* Pattern node; Ancestor of nodes that set satisfaction policy
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
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
* Include class interface
*/
require_once ( dirname ( __FILE__ ) . '/Node_Interface.php' );

/**
* @package MapFilter
*/
abstract class MapFilter_Pattern_Tree_Node
    extends MapFilter_Pattern_Tree
    implements MapFilter_Pattern_Tree_Node_Interface
{

  /**
  * Node Followers
  * @var Array ( MapFilter_Pattern_Tree )
  */
  private $content = Array ();

  /**
  * Fluent Method; Set content
  * @param Array ( MapFilter_Pattern_Tree )
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
  * Get node followers
  * @return &Array ( MapFilter_Pattern_Tree )
  */
  public function &getContent () {
  
    return $this->content;
  }
  
  /**
  * PickUp Nodes
  * @param MapFilter_Pattern_PickUpParam
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {
      return;
    }
  
    foreach ( $this->getContent () as $follower ) {

      $follower->pickUp ( $param );
    }
    
    return;
  }
  
  /**
  * Clone node followers
  */
  public function __clone () {
  
    $content = $this->getContent ();
    foreach ( $content as &$follower ) {
    
      $follower = clone ( $follower );
    }
    
    return;
  }
}
