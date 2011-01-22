<?php
/**
 * Pattern Attribute.
 *
 * PHP Version 5.1.0
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
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.4
 */

/**
 * Pattern attribute.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Attribute
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.4
 */
class MapFilter_TreePattern_Tree_Attribute {

  /**
   * Node attribute.
   *
   * @since     0.5.4
   *
   * @var       String          $_attribute
   */
  private $_attribute = NULL;
  
  /**
   * Attr existence default value.
   *
   * @since     0.5.4
   *
   * @var       String          $_existenceDefault
   */
  private $_existenceDefault = NULL;
  
  /**
   * Attr validation default value.
   *
   * @since     0.5.4
   *
   * @var       String          $_validationDefault
   */
  private $_validationDefault = NULL;
  
  /**
   * Attr value Pattern.
   *
   * @since     0.5.4
   *
   * @var       String          $_valuePattern
   */
  private $_valuePattern = NULL;
  
  /**
   * Attr value Replacement.
   *
   * @since     $NEXT$
   *
   * @var       String          $_valueReplacement
   */
  private $_valueReplacement = NULL;

  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * @since     0.5.4
   *
   * @var       String          $_iterator
   */
  private $_iterator = 0;

  /**
   * User query to examinate.
   *
   * @since     0.5.4
   *
   * @var       Array|ArrayAccess       $_query
   */
  private $_query = Array ();
  
  /**
   * Attribute value.
   *
   * @since     0.5.4
   *
   * @var       String          $_value
   */
  private $_value = NULL;

  /**
   * Set attribute.
   *
   * @since     0.5.4
   *
   * @param     String          $attribute              An attribute to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   * @see       getAttribute
   */
  public function setAttribute ( $attribute ) {

    $this->_attribute = $attribute;
    return $this;
  }
  
  /**
   * Get node attribute.
   *
   * @since     0.5.4
   *
   * @return    String          A node attribute.
   * @see       setAttribute
   */
  public function getAttribute () {
  
    return $this->_attribute;
  }
  
  /**
   * Set default value.
   *
   * @since     0.5.4
   *
   * @param     String          $default        A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   * @see       setValidationDefault, setExistenceDefault
   */
  public function setDefault ( $default ) {

    return $this
        ->setExistenceDefault ( $default )
        ->setValidationDefault ( $default )
    ;
  }
  
  /**
   * Set validation default value.
   *
   * @since     0.5.4
   *
   * @param     String          $validationDefault      A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   * @see       setDefault, setExistenceDefault
   */
  public function setValidationDefault ( $validationDefault ) {

    $this->_validationDefault = $validationDefault;
    return $this;
  }
  
  /**
   * Set existence default value.
   *
   * @since     0.5.4
   *
   * @param     String          $existenceDefault       A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   * @see       setDefault, setValidationDefault
   */
  public function setExistenceDefault ( $existenceDefault ) {

    $this->_existenceDefault = $existenceDefault;
    return $this;
  }

  /**
   * Set valuePattern.
   *
   * @since     0.5.4
   *
   * @param     String          $valuePattern   A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setValuePattern ( $valuePattern ) {

    $this->_valuePattern = MapFilter_TreePattern_Tree::sanitizeRegExp (
        $valuePattern
    );

    return $this;
  }
  
  /**
   * Set valueReplacement.
   *
   * @since     $NEXT$
   *
   * @param     String          $valueReplacement   A valueReplacement to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setValueReplacement ( $valueReplacement ) {

    $this->_valueReplacement = $valueReplacement;

    return $this;
  }
  
  /**
   * Set iterator.
   *
   * @since     0.5.4
   *
   * @param     Int             $iterator       An iterator value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setIterator ( $iterator ) {

    $this->_iterator = $iterator;
    return $this;
  }

  /**
   * Set iterator.
   *
   * @since     0.5.4
   *
   * @param     Array|ArrayAccess       $query          User query.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setQuery ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    $this->_value = NULL;
    $this->_query = $query;
    return $this;
  }

  /**
   * Set attribute value.
   *
   * @since     $NEXT$
   *
   * @param     Mixed                   $value          Value.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setValue ( $value ) {
  
    $this->_value = $value;
    return $this;
  }

  /**
   * Get attribute value.
   *
   * @since     0.5.4
   *
   * @return    Mixed
   */
  public function getValue () {
  
    return $this->_value;
  }

  /**
   * Determine whether an attribute is present in query.
   *
   * @since     0.5.4
   *
   * @return    Bool
   * @see       isValid
   */
  public function isPresent () {
  
    $present = array_key_exists (
        $this->_attribute,
        $this->_query
    );

    if ( !$present && $this->_existenceDefault !== NULL ) {
      
      $this->_value = $this->_existenceDefault;
      for ( $a = 0; $a < $this->_iterator; $a++ ) {
      
        $this->_value = Array ( $this->_value );
      }

      $this->_query[ $this->_attribute ] = $this->_value;
      $present = TRUE;
    }

    return $present;
  }
  
  /**
   * Determine whether a value is valid.
   *
   * @since     0.5.4
   *
   * @return    Bool
   * @see       isPresent
   */
  public function isValid () {
  
    if ( !$this->isPresent () ) return FALSE;

    $this->_value = $this->_query[ $this->_attribute ];
  
    $valid = $this->_validate (
        $this->_value
    );
    
    if ( !$valid && $this->_validationDefault !== NULL ) {
      
      $this->_value = $this->_validationDefault;
      for ( $a = 0; $a < $this->_iterator; $a++ ) {
      
        $this->_value = Array ( $this->_value );
      }

      $this->_query[ $this->_attribute ] = $this->_value;
      $valid = TRUE;
    }
    
    return $valid;
  }

  /**
   * Validate arbitrary iterator structure
   *
   * @since     0.5.4
   *
   * @param     Mixed           $valueCandidate
   *
   * @param     Int             $level
   *
   * @return    Bool
   */
  private function _validate ( &$valueCandidate, $level = 0 ) {

    assert ( is_int ( $level ) );

    assert ( $level <= $this->_iterator );

    if ( $level === $this->_iterator ) {
  
      $valid = $this->_validateScalarValue ( $valueCandidate );
      
      if ( !$valid && $this->_validationDefault !== NULL ) {
      
        $valueCandidate = $this->_validationDefault;
        $valid = TRUE;
      }
      
      return $valid;
    }
    
    if (
        !is_array ( $valueCandidate )
        && !( $valueCandidate instanceof Traversable )
    ) {

      return FALSE;
    }

    $values = Array ();
    foreach ( $valueCandidate as $singleValueCandidate ) {
    
      if (
          $this->_validate ( $singleValueCandidate, $level + 1 )
      ) {
      
        $values[] = $singleValueCandidate;
      }
    }

    $valueCandidate = $values;
    
    return ( $values !== Array () );
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
  
    if (
        is_array ( $valueCandidate ) || $valueCandidate instanceof Traversable
    ) {
    
      return FALSE;
    }
  
    $fits = self::valueFits (
        $valueCandidate,
        $this->_valuePattern
    );

    if ( !$fits ) return FALSE;
    
    if ( $this->_valueReplacement !== NULL ) {
      
      $valueCandidate = preg_replace (
          $this->_valuePattern,
          $this->_valueReplacement,
          $valueCandidate
      );
    }
    
    return $fits;
  }

  /**
   * Test whether a ForValue condition on tree node fits given pattern.
   *
   * @since     0.5.4
   *
   * @param     Mixed           &$valueCandidate 	A value to fit.
   * @param     String|NULL     $pattern                Value pattern.
   *
   * @return    Bool            Does the value fit.
   */
  public static function valueFits ( &$valueCandidate, $pattern ) {

    if ( $pattern === NULL ) return TRUE;

    $matchCount = preg_match (
        $pattern,
        $valueCandidate
    );

    assert ( $matchCount !== FALSE );
    
    return (Bool) $matchCount;
  }

  /**
   * Convert attribute to string.
   *
   * @since     0.5.4
   *
   * @return    String          String representation of node.
   */
  public function __toString () {
  
    return (String) $this->_attribute;
  }
}
