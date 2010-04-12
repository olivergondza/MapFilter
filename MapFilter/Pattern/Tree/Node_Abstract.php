<?php
/**
* Pattern node; Ancestor of nodes that set satisfaction policy
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../Tree_Abstract.php' );

abstract class MapFilter_Pattern_Tree_Node_Abstract
    extends MapFilter_Pattern_Tree_Abstract
{

  /**
  * Node Followers
  * @var: Array ( MapFilter_Pattern_Tree_Abstract )
  */
  private $content = Array ();

  /**
  * Fluent Method; Set content
  * @content: Array ( MapFilter_Pattern_Tree_Abstract )
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
  * Get node followers
  * @&return: Array ( MapFilter_Pattern_Tree_Abstract )
  */
  public function &getContent () {
  
    return $this->content;
  }
  
  /**
  * PickUp Nodes
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {

      return;
    }
  
    /** Set flag from satisfied node */
    if ( $this->flag !== NULL ) {
    
      $param->flags[] = $this->flag;
    }

    foreach ( $this->getContent () as $follower ) {

      $follower->pickUp ( $param );
    }
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
