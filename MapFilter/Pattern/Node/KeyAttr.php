<?php
/**
* KeyAttr Pattern node;
* Since this node has something similar with Policy node extends this class
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Policy.php' );

final class MapFilter_Pattern_Node_KeyAttr extends
    MapFilter_Pattern_Node_Policy
{

  /**
  * Node attribute
  * @var: String
  */
  public $attribute = "";
  
  /**
  * Attribute value
  * @var:String
  */
  public $value = "";
  
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
  * @&query: Array
  * @&asserts: Array ( String )
  * @return: Bool
  */
  public function satisfy ( Array &$query, Array &$asserts ) {

    $attrName = $this->attribute;

    $attrExists = self::attrPresent ( $attrName, $query );

    if ( !$attrExists ) {

      return $this->setSatisfied ( FALSE,  $asserts );
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $this->getContent () as $follower ) {
      
      $fits = self::valueFits (
          $query[ $attrName ],
          $follower->valueFilter
      );
      
      if ( $fits ) {

        $satisfied = $follower->satisfy ( $query, $asserts );
        
        if ( $satisfied ) {
        
          $this->value = $query[ $attrName ];
        }
        
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

  /**
  * Determine whether a node has an follower
  * return: Bool
  */
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
