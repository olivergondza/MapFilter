<?php
/**
* Attr Pattern node
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../../Tree.php' );
require_once ( dirname ( __FILE__ ) . '/../Attribute_Interface.php' );

final class MapFilter_Pattern_Tree_Leaf_Attr
    extends MapFilter_Pattern_Tree
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
  * Attr default value
  * @var String
  */
  public $default = NULL;
  
  /**
  * Attr value Pattern
  * @var String
  */
  public $valuePattern = NULL;
  
  /**
  * Fluent Method; Set attribute
  * @param String
  */
  public function setAttribute ( $attribute ) {

    $this->attribute = $attribute;
    return $this;
  }
  
  /**
  * Fluent Method; Set default
  * @param String
  */
  public function setDefault ( $default ) {

    $this->default = $default;
    return $this;
  }

  /**
  * Fluent Method; Set valuePattern
  * @param String
  */
  public function setValuePattern ( $valuePattern ) {

    $this->valuePattern = $valuePattern;
    return $this;
  }

  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    /** If argument exists */
    $present = self::attrPresent (
        $this->attribute,
        $param->query
    );
    
    if ( $present ) {
    
      /** And matches pattern */
      $fitsPattern = self::valueFits (
          $param->query[ $this->attribute ],
          $this->valuePattern
      );

      if ( $fitsPattern ) {
    
        $this->value = $param->query[ $this->attribute ];
    
        return $this->setSatisfied ( TRUE, $param );
      }
    }
    
    /** Set default if defined */
    if ( $this->default !== NULL ) {
      
      $this->value = $this->default;

      return $this->setSatisfied ( TRUE, $param );
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

    return;
  }
  
  /**
  * Attr node has nothing to clone
  */
  public function __clone () {
  
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
