<?php
/**
* Attr Pattern node.
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
* @file		MapFilter/TreePattern/Tree/Leaf.php
*/
require_once ( dirname ( __FILE__ ) . '/../Leaf.php' );

/**
* @file		MapFilter/TreePattern/Tree/Attribute/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/../Attribute/Interface.php' );

/**
* MapFilter pattern tree attribute leaf
*
* @class	MapFilter_TreePattern_Tree_Leaf_Attr
* @ingroup	gtreepattern
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.4
*/
final class MapFilter_TreePattern_Tree_Leaf_Attr extends
    MapFilter_TreePattern_Tree_Leaf
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
  private $attribute = "";
  
  /**
  * Attribute value
  *
  * @since	0.4
  *
  * @var	String	$value
  */
  private $value = "";
  
  /**
  * Attr default value
  *
  * @since	0.4
  *
  * @var	String	$default
  */
  private $default = NULL;
  
  /**
  * Attr value Pattern
  *
  * @since	0.4
  *
  * @var	String	$valuePattern
  */
  private $valuePattern = NULL;
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::getAttribute()}
  */
  public function getAttribute () {
  
    return $this->attribute;
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::setAttribute()}
  */
  public function setAttribute ( $attribute ) {

    $this->attribute = $attribute;
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::setDefault()}
  */
  public function setDefault ( $default ) {

    $this->default = $default;
    return $this;
  }

  /**
  * @copyfull{MapFilter_TreePattern_Tree_Interface::setValuePattern()}
  */
  public function setValuePattern ( $valuePattern ) {

    $this->valuePattern = $valuePattern;
    return $this;
  }

  /**
  * @copybrief		MapFilter_TreePattern_Tree_Interface::satisfy()
  *
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent by any further potentially satisfied follower.
  *
  * @copydetails	MapFilter_TreePattern_Tree_Interface::satisfy()
  */
  public function satisfy ( MapFilter_TreePattern_SatisfyParam $param ) {
  
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
  * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUp()}
  */
  public function pickUp ( Array $result ) {

    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$this->isSatisfied () ) {

      return Array ();
    }
  
    $result[ (String) $this ] = $this->value;

    return $result;
  }
  
  /**
  * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::__toString()}
  */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
