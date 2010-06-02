<?php
/**
* KeyAttr Pattern node
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.4
*/

/**
* @file		MapFilter/TreePattern/Tree/Node.php
*/
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
* @file		MapFilter/TreePattern/Tree/Attribute/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/../Attribute/Interface.php' );

/**
* MapFilter pattern tree SetAttribute node
*
* @class	MapFilter_TreePattern_Tree_Node_KeyAttr
* @ingroup	gtreepattern
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.4
*/
final class MapFilter_TreePattern_Tree_Node_KeyAttr extends
    MapFilter_TreePattern_Tree_Node
implements
    MapFilter_TreePattern_Tree_Attribute_Interface
{

  /**
  * Node attribute
  *
  * @since	0.4
  *
  * @var	String	$attribute
  */
  public $attribute = "";
  
  /**
  * Attribute value
  *
  * @since	0.4
  *
  * @var	String	$value
  */
  public $value = "";
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::setAttribute()}
  */
  public function setAttribute ( $attribute ) {
  
    $this->attribute = $attribute;
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::getAttribute()}
  */
  public function getAttribute () {
  
    return $this->attribute;
  }

  /**
  * @copybrief		MapFilter_TreePattern_Tree_Interface::satisfy()
  *
  * Find a follower with a valueFilter that fits and try to satisfy it.
  *
  * @copydetails	MapFilter_TreePattern_Tree_Interface::satisfy()
  */
  public function satisfy ( &$query, Array &$asserts ) {

    $attrName = $this->attribute;

    $attrExists = self::attrPresent ( $attrName, $query );

    if ( !$attrExists ) {

      return $this->setSatisfied ( FALSE, $asserts );
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $this->getContent () as $follower ) {
      
      $fits = self::valueFits (
          $query[ $attrName ],
          $follower->getValueFilter ()
      );
      
      if ( !$fits ) {
      
        continue;
      }

      $satisfied = $follower->satisfy ( $query, $asserts );
        
      if ( $satisfied ) {
          
        $this->value = $query[ $attrName ];
      }
        
      return $this->setSatisfied ( $satisfied, $asserts );
    }

    return $this->setSatisfied ( FALSE, $asserts );
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUp()}
  */
  public function pickUp ( Array $result ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {

      return Array ();
    }
  
    $result[ (String) $this ] = $this->value;

    foreach ( $this->getContent () as $follower ) {

      $result = array_merge (
          $result,
          $follower->pickUp ( $result )
      );
    }

    return $result;
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::__toString()}
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
