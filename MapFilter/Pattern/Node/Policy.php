<?php
/**
* Policy Pattern node; Ancestor of nodes that no nothing else just set
* a satisfaction policy
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

abstract class MapFilter_Pattern_Node_Policy extends
    MapFilter_Pattern_Node_Abstract
{

  /**
  * Node Followers
  * @var: Array ( MapFilter_Pattern_Node_Abstract )
  */
  private $content = Array ();

  /**
  * Fluent Method; Set content
  * @content: Array ( MapFilter_Pattern_Node_Abstract )
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
  * Get node followers
  * @return: Array ( MapFilter_Pattern_Node_Abstract )
  */
  public function &getContent () {
  
    return $this->content;
  }
  
  /**
  * Determine whether a node has an attribute
  * return: Bool
  */
  public function hasAttr () {
  
    return FALSE;
  }
  
  /**
  * Determine whether a node has an followers
  * return: Bool
  */
  public function hasFollowers () {
  
    return TRUE;
  }
}
