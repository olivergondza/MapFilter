<?php
/**
 * Pattern Leaf.
 *
 * Ancestor of Leaves.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     LGPL
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

/**
 * @file        MapFilter/TreePattern/Tree.php
 */
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Leaf/Interface.php' );

/**
 * Abstract class for pattern tree leaf.
 *
 * @class       MapFilter_TreePattern_Tree_Leaf
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
abstract class
    MapFilter_TreePattern_Tree_Leaf
extends
    MapFilter_TreePattern_Tree
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * Node attribute.
   *
   * @since     0.4
   *
   * @var       String          $attribute
   */
  protected $attribute = "";
  
  /**
   * Attribute value.
   *
   * @since     0.4
   *
   * @var       String          $value
   */
  protected $value = NULL;
  
  /**
   * Attr default value.
   *
   * @since     0.5.2
   *
   * @var       String          $default
   */
  protected $default = NULL;
  
  /**
   * Attr value Pattern.
   *
   * @since     0.5.2
   *
   * @var       String          $valuePattern
   */
  protected $valuePattern = NULL;
  
  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * Possible values are 'no', 'yes' and 'auto'.
   *
   * @since     0.5.2
   *
   * @var       String          $iterator
   */
  protected $iterator = 'no';

  /**
   * Node content.
   *
   * @since     0.5.2
   *
   * @var       Array           $content
   */
  protected $content = Array ();

  /**
   * Get node followers.
   *
   * @since     0.5.2
   *
   * @return    Array           Node content reference
   */
  public function &getContent () {
  
    return $this->content;
  }

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
   * @copyfull{MapFilter_TreePattern_Tree_Leaf_Interface::getAttribute()}
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
   * Determine whether the value is valid or not.
   *
   * @since             0.5.2
   *
   * @param             Mixed          &$valueCandidate
   *
   * @return            Bool           Valid or not.
   */
  private function validateScalarValue ( &$valueCandidate ) {
  
    return self::valueFits (
        $valueCandidate,
        $this->valuePattern
    );
  }

  /**
   * Determine whether the value is valid or not and possibly set a default
   * value.
   *
   * @since             0.5.2
   *
   * @param             Mixed          &$valueCandidate
   *
   * @return            Bool           Valid or not.
   */
  protected function validateValue ( &$valueCandidate ) {
  
    $arrayUsed = is_array ( $valueCandidate );
    if ( $arrayUsed ) {
    
      $valueCandidate = array_filter (
          $valueCandidate,
          Array ( $this, 'validateScalarValue' )
      );

      $validated = !( $valueCandidate === Array () );

    } else {
    
      $validated = $this->validateScalarValue ( $valueCandidate );
    }
    
    if ( $validated ) {
    
      return TRUE;
    }
    
    if ( $this->default !== NULL ) {
      
      $valueCandidate = (
          self::ITERATOR_VALUE_YES === $this->iterator
          || $arrayUsed
      )
          ? Array ( $this->default )
          : $this->default;

      return TRUE;
    }
    
    return FALSE;
  }

  /**
   * Convert iterator to an array.
   *
   * @since             0.5.2
   *
   * @param             Mixed     $valueCandidate
   * 
   * @return            Mixed
   */
  protected function convertIterator ( $valueCandidate ) {

    return ( $valueCandidate instanceof Iterator )
        ? iterator_to_array ( $valueCandidate, FALSE )
        : $valueCandidate
    ;
  }

  /**
   * Assert type mismatch.
   *
   * If there is no match between a declared value type and the current
   * value type an exception is going to be risen.
   *
   * @since     0.5.2
   *
   * @param     Bool            $isIterator             Is iterator or not.
   * @param     String          $valueType              Type of given value.
   *
   * @return    NULL
   *
   * @throws    MapFilter_TreePattern_Tree_Leaf_Exception::ARRAY_ATTR_VALUE
   *            MapFilter_TreePattern_Tree_Leaf_Exception::SCALAR_ATTR_VALUE
   */
  protected function assertTypeMismatch ( $isIterator, $valueType ) {
  
    
    if ( 
        ( self::ITERATOR_VALUE_NO === $this->iterator ) && $isIterator
    ) {
    
      throw new MapFilter_TreePattern_Tree_Leaf_Exception (
          MapFilter_TreePattern_Tree_Leaf_Exception::ARRAY_ATTR_VALUE,
          Array ( $this->attribute )
      );
    }

    if ( 
        ( self::ITERATOR_VALUE_YES === $this->iterator ) && !$isIterator
    ) {

      throw new MapFilter_TreePattern_Tree_Leaf_Exception (
          MapFilter_TreePattern_Tree_Leaf_Exception::SCALAR_ATTR_VALUE,
          Array ( $this->attribute, $valueType )
      );
    }
  }

  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUp()}
   */
  public function pickUp ( Array $result ) {

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
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUpFlags()}
   */
  public function pickUpFlags ( Array $flags ) {
  
    if ( !$this->isSatisfied () ) {
    
      return $flags;
    }
    
    if ( $this->flag !== NULL ) {
  
        $flags[] = $this->flag;
    }
    
    return $flags;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::__clone()}
   * Overwrite MapFilter_Pattern_Tree deep cloning method
   */
  public function __clone () {}
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Leaf_Interface::__toString()}
   */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
