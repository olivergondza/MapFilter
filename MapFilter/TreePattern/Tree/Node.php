<?php
/**
 * Ancestor of pattern tree nodes.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */

/**
 * @file        MapFilter/TreePattern/Tree.php
 */
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Node/Interface.php' );

/**
 * Abstract class for pattern tree node,
 *
 * @class       MapFilter_TreePattern_Tree_Node
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */
abstract class MapFilter_TreePattern_Tree_Node extends
    MapFilter_TreePattern_Tree
implements
    MapFilter_TreePattern_Tree_Node_Interface
{

  /**
   * Node Followers
   *
   * @since     0.3
   *
   * @var       Array           $content
   */
  protected $content = Array ();

  /**
   * Fluent Method; Set content
   *
   * @since     0.3
   *
   * @param     Array           $content                A content to set
   *
   * @return    self
   */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
   * Get node followers
   *
   * @since     0.3
   *
   * @return    Array           Node content reference
   */
  public function &getContent () {
  
    return $this->content;
  }
  
  /**
   * PickUp Nodes
   *
   * @since     0.3
   *
   * @param     Array           $result
   */
  public function pickUp ( Array $result ) {

    /**
     * Set an assertion for nodes that hasn't been satisfied and stop the
     * recursion
     */
    if ( !$this->isSatisfied () ) {

      return Array ();
    }
  
    foreach ( $this->getContent () as $follower ) {

      $result = array_merge (
          $result,
          $follower->pickUp ( $result )
      );
    }
    
    return $result;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUpFlags}
   */
  public function pickUpFlags ( Array $flags ) {
  
    if ( !$this->isSatisfied () ) {
    
      return $flags;
    }
    
    if ( $this->flag !== NULL ) {
    
      $flags[] = $this->flag;
    }
    
    foreach ( $this->getContent () as $follower ) {

      $flags = $follower->pickUpFlags ( $flags );
    }
    
    return $flags;
  }
  
  /**
   * Clone node followers
   *
   * @note This method uses deep cloning
   *
   * @since     0.3
   */
  public function __clone () {
  
    $content = $this->getContent ();
    foreach ( $content as &$follower ) {
    
      $follower = clone ( $follower );
    }
  }
}
