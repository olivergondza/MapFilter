<?php
/**
 * Attr Pattern leaf.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

/**
 * @file        MapFilter/TreePattern/Tree/Leaf.php
 */
require_once ( dirname ( __FILE__ ) . '/../Leaf.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Attribute/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/../Attribute/Interface.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Attribute/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/../Attribute/Exception.php' );

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @class       MapFilter_TreePattern_Tree_Leaf_Attr
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
final class MapFilter_TreePattern_Tree_Leaf_Attr extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Attribute_Interface
{

  /**
   * Node attribute
   *
   * @since     0.4
   *
   * @var       String          $attribute
   */
  private $attribute = "";
  
  /**
   * Attribute value
   *
   * @since     0.4
   *
   * @var       String          $value
   */
  private $value = NULL;
  
  /**
   * Attr default value
   *
   * @since     0.4
   *
   * @var       String          $default
   */
  private $default = NULL;
  
  /**
   * Attr value Pattern
   *
   * @since     0.4
   *
   * @var       String          $valuePattern
   */
  private $valuePattern = NULL;
  
  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * Possible values are 'no', 'yes' and 'auto'.
   *
   * @since     0.5.2
   *
   * @var       String
   */
  private $iterator = 'no';

  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setIterator()}
   */
  public function setIterator ( $iterator ) {

    $this->iterator = $iterator;
    return $this;
  }
  
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
   * Determine whether the value is valid or not and possibly set a default
   * value.
   *
   * @since             0.5.2
   *
   * @param             Mixed          $valueCandidate
   *
   * @return            Bool           Valid or not
   */
  private function validateValue ( &$valueCandidate ) {
  
    $fitsPattern = self::valueFits (
        $valueCandidate,
        $this->valuePattern
    );

    if ( $fitsPattern ) {
  
      return TRUE;
    }
    
    if ( $this->default !== NULL ) {
      
      $valueCandidate = $this->default;
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  /**
   * @copybrief         MapFilter_TreePattern_Tree_Interface::satisfy()
   *
   * Attr leaf is satisfied when its attribute occurs in user query and its
   * value matches the optional pattern defined by valuePattern attribute. 
   * When this does not happen this node still can be satisfied if its
   * default value is sat: attribute will have that default value and leaf
   * will be satisfied.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy()
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    $present = self::attrPresent (
        $this->attribute,
        $query
    );

    /**
     * If an attribute is not present and there is a default value defined,
     * the default value is going to be used as a value and posibly wrapped
     * into the array if the attribute is flaged as the iterator attribute.
     */
    if ( !$present ) {
    
      if ( $this->default === NULL ) {
      
        $this->setAssertValue ( $asserts );
        return $this->satisfied = FALSE;
      }
      
      $this->value = ( self::ARRAY_VALUE_YES === $this->iterator )
          ? Array ( $this->default )
          : $this->default
      ;
      return $this->satisfied = TRUE;
    }
    
    /**
     * Satisfy actual attribute value
     */
    $valueCandidate = $query[ $this->attribute ];
    $valueCandidate = ( $valueCandidate instanceof Iterator )
        ? iterator_to_array ( $valueCandidate, FALSE )
        : $valueCandidate
    ;

    $currentArrayValue = is_array ( $valueCandidate );

    /**
     * If there is no match between a declared value type and the current
     * value type an exception is going to be risen.
     */
    if ( 
        ( self::ARRAY_VALUE_NO === $this->iterator ) && $currentArrayValue
    ) {
    
      throw new MapFilter_TreePattern_Tree_Attribute_Exception (
          MapFilter_TreePattern_Tree_Attribute_Exception::ARRAY_ATTR_VALUE,
          Array ( $this->attribute )
      );
    }

    if ( 
        ( self::ARRAY_VALUE_YES === $this->iterator ) && !$currentArrayValue
    ) {

      throw new MapFilter_TreePattern_Tree_Attribute_Exception (
          MapFilter_TreePattern_Tree_Attribute_Exception::SCALAR_ATTR_VALUE,
          Array ( $this->attribute, gettype ( $valueCandidate ) )
      );
    }

    /** Dispatch single value */
    if ( !$currentArrayValue ) {
    
      $this->satisfied = $this->validateValue ( $valueCandidate );
      $this->value = $valueCandidate;

      if ( !$this->satisfied ) {
      
        $this->setAssertValue ( $asserts, $this->value );
      }

      return $this->satisfied;
    }

    /** Dispatch array value */
    $assertValue = NULL;
    foreach ( $valueCandidate as &$singleCandidate ) {
    
      $valid = $this->validateValue ( $singleCandidate );
      
      if ( $valid ) {

        $this->value[] = $singleCandidate;
      } else {
      
        $assertValue[] = $singleCandidate;
      }
    }

    $this->satisfied = (Bool) count ( $this->value );

    if ( (Bool) count ( $assertValue ) || !$this->satisfied ) {
    
      $this->setAssertValue ( $asserts, $assertValue );
    }

    return $this->satisfied;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUp()}
   */
  public function pickUp ( Array $result ) {

    /**
     * Set assertion for nodes that hasn't been satisfied and stop the
     * recursion
     */
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
