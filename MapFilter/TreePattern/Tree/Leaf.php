<?php
/**
 * Pattern Leaf.
 *
 * PHP Version 5.1.0
 *
 * This file is part of MapFilter package.
 *
 * This file is part of MapFilter package.
 *
 * MapFilter is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 *                
 * MapFilter is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 *                              
 * You should have received a copy of the GNU Lesser General Public License
 * along with MapFilter.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category Pear
 * @package  MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
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
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
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
   * Set attribute.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $attribute              An attribute to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    A pattern with new attribute.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAttribute ( $attribute ) {

    $this->attribute = $attribute;
    return $this;
  }
  
  /**
   * Set default value.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $default        A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    A pattern with new default value.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setDefault ( $default ) {

    $this->default = $default;
    return $this;
  }
  
  /**
   * Set valueFilter.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $valuePattern   A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    A pattern with new valueFilter.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setValuePattern ( $valuePattern ) {

    $this->valuePattern = $valuePattern;
    return $this;
  }
  
  /**
   * Get node attribute.
   *
   * @since     0.4
   *
   * @return    String          A node attribute.
   * @see       setAttribute()
   */
  public function getAttribute () {
  
    return $this->attribute;
  }

  /**
   * Set iterator.
   *
   * A Fluent Method.
   *
   * @since     0.5.2
   *
   * @param     String          $iterator       An iterator value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    New pattern with iterator.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setIterator ( $iterator ) {

    $this->iterator = $iterator;
    return $this;
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
          Array ( $this, '_validateScalarValue' )
      );

      $validated = !( $valueCandidate === Array () );

    } else {
    
      $validated = $this->_validateScalarValue ( $valueCandidate );
    }
    
    if ( $validated ) return TRUE;
    
    if ( $this->default === NULL ) return FALSE;

    $valueCandidate = (
        self::ITERATOR_VALUE_YES === $this->iterator
        || $arrayUsed
    )
        ? Array ( $this->default )
        : $this->default;

    return TRUE;
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
  private function _validateScalarValue ( &$valueCandidate ) {
  
    return self::valueFits (
        $valueCandidate,
        $this->valuePattern
    );
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
   * @throws    MapFilter_TreePattern_Tree_Exception::ARRAY_ATTR_VALUE
   *            MapFilter_TreePattern_Tree_Exception::SCALAR_ATTR_VALUE
   */
  protected function assertTypeMismatch ( $isIterator, $valueType ) {
  
    $wantIterator = self::ITERATOR_VALUE_YES === $this->iterator;
    $wantScalar = self::ITERATOR_VALUE_NO === $this->iterator;
    
    if ( $wantScalar && $isIterator ) {
    
      throw new MapFilter_TreePattern_Tree_Leaf_Exception (
          MapFilter_TreePattern_Tree_Leaf_Exception::ARRAY_ATTR_VALUE,
          Array ( $this->attribute )
      );
    }

    if ( $wantIterator && !$isIterator ) {

      throw new MapFilter_TreePattern_Tree_Leaf_Exception (
          MapFilter_TreePattern_Tree_Leaf_Exception::SCALAR_ATTR_VALUE,
          Array ( $this->attribute, $valueType )
      );
    }
  }

  /**
   * Pick-up satisfaction results.
   *
   * @since     0.3
   *
   * @param     Array           $result
   * @return    Array
   */
  public function pickUp ( Array $result ) {

    if ( !$this->isSatisfied () ) return Array ();
  
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
   * Get filtering flags.
   *
   * @since     0.5.1
   *
   * @param     Array           $flags
   *
   * @return    Array
   */
  public function pickUpFlags ( Array $flags ) {
  
    if ( !$this->isSatisfied () ) return $flags;
    
    if ( $this->flag !== NULL ) {
  
      if ( !in_array ( $this->flag, $flags ) ) {
        $flags[] = $this->flag;
      }
    }
    
    return $flags;
  }
  
  /**
   * Clone node followers.
   *
   * @since     0.3
   * 
   * Overwrite MapFilter_Pattern_Tree deep cloning method
   *
   * @return    MapFilter_TreePattern_Tree_Leaf
   */
  public function __clone () {}
  
  /**
   * Convert node to string.
   *
   * @since     0.4
   *
   * @return    String          String representation of node.
   */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
