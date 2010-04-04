<?php
/**
* Policy Pattern node; Ancestor of nodes thad no nothing else just set
* a satisfaction policy
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
