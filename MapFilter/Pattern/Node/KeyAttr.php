<?php
/**
* KeyAttr Pattern node 
*/
require_once ( dirname ( __FILE__ ) . '/Abstract.php' );

class MapFilter_Pattern_Node_KeyAttr extends MapFilter_Pattern_Node_Abstract {

  /**
  * Node Followers
  * @var: Array ( MapFilter_Pattern_Node_Interface )
  */
  public $content = Array ();

  /**
  * Node attribute
  * @var: String
  */
  public $attribute = "";

  /**
  * Fluent Method; Set content
  * @content: Array
  */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }
  
  /**
  * Fluent Method; Set attribute
  * @attribute: String
  */
  public function setAttribute ( $attribute ) {
  
    $this->attribute = $attribute;
    return $this;
  }

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    $attrName = $this->attribute;

    $attrExists = self::attrPresent ( $attrName, $query );

    if ( !$attrExists ) {

      return $this->setSatisfied ( FALSE,  $asserts );
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $this->content as $follower ) {
      
      $fits = self::valueFits (
          $query[ $attrName ],
          $follower->valueFilter
      );
      
      if ( $fits ) {

        $satisfied = $follower->satisfy ( $query, $asserts );
        
        return $this->setSatisfied ( $satisfied, $asserts );
      } 
    }

    return $this->setSatisfied ( FALSE, $asserts );
  }
  
  /**
  * Determine whether a node has an attribute
  * return: Bool
  */
  public function hasAttr () {
  
    return TRUE;
  }
  
  public function hasFollowers () {
  
    return TRUE;
  }
  
  /**
  * Cast to string
  * @return: String
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
