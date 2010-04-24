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
require_once ( dirname ( __FILE__ ) . '/../Node.php' );
require_once ( dirname ( __FILE__ ) . '/../Attribute_Interface.php' );

final class MapFilter_Pattern_Tree_Node_KeyAttr
    extends MapFilter_Pattern_Tree_Node
    implements MapFilter_Pattern_Tree_Attribute_Interface
{

  /**
  * Node attribute
  * @var String
  */
  public $attribute = "";
  
  /**
  * Attribute value
  * @var String
  */
  public $value = "";
  
  /**
  * Fluent Method; Set attribute
  * @param String
  */
  public function setAttribute ( $attribute ) {
  
    $this->attribute = $attribute;
    return $this;
  }

  /**
  * Find a follower with a valueFilter that fits and try to satisfy it.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {

    $attrName = $this->attribute;

    $attrExists = self::attrPresent ( $attrName, $param->query );

    if ( !$attrExists ) {

      return $this->setSatisfied ( FALSE, $param );
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $this->getContent () as $follower ) {
      
      $fits = self::valueFits (
          $param->query[ $attrName ],
          $follower->valueFilter
      );
      
      if ( !$fits ) {
      
        continue;
      }

      $satisfied = $follower->satisfy ( $param );
        
      if ( $satisfied ) {
          
        $this->value = $param->query[ $attrName ];
      }
        
      return $this->setSatisfied ( $satisfied, $param );
    }

    return $this->setSatisfied ( FALSE, $param );
  }
  
  /**
  * Pick-up results
  * @param MapFilter_Pattern_PickUpParam
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {
      return;
    }
  
    $param->data[ (String) $this ] = $this->value;

    foreach ( $this->getContent () as $follower ) {

      $follower->pickUp ( $param );
    }

    return;
  }
  
  /**
  * Cast to string
  * @return String
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
