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
 * @author   Oliver Gondža <324706@mail.muni.cz>
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
 * @author   Oliver Gondža <324706@mail.muni.cz>
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
   * @var       String          $attribute
   */
  private $attribute = "";
  
  /**
   * Attr default value.
   *
   * @since     0.5.4
   *
   * @var       String          $default
   */
  private $default = NULL;
  
  /**
   * Attr value Pattern.
   *
   * @since     0.5.4
   *
   * @var       String          $valuePattern
   */
  private $valuePattern = NULL;

  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * Possible values are 'no', 'yes' and 'auto'.
   *
   * @since     0.5.4
   *
   * @var       String          $iterator
   */
  private $iterator = 'no';

  /**
   * User query to examinate.
   *
   * @since     0.5.4
   *
   * @var       Array|ArrayAccess       $query
   */
  private $query = Array ();
  
  /**
   * Attribute value.
   *
   * @since     0.5.4
   *
   * @var       String          $value
   */
  private $value = '';

  /**
   * Set attribute.
   *
   * A Fluent Method.
   *
   * @since     0.5.4
   *
   * @param     String          $attribute              An attribute to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
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
   * @since     0.5.4
   *
   * @param     String          $default        A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
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
   * @since     0.5.4
   *
   * @param     String          $valuePattern   A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setValuePattern ( $valuePattern ) {

    $this->valuePattern = $valuePattern;
    return $this;
  }
  
  /**
   * Set iterator.
   *
   * A Fluent Method.
   *
   * @since     0.5.4
   *
   * @param     String          $iterator       An iterator value to set.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setIterator ( $iterator ) {

    $this->iterator = $iterator;
    return $this;
  }

  /**
   * Set iterator.
   *
   * A Fluent Method.
   *
   * @since     0.5.4
   *
   * @param     Array|ArrayAccess       $query          User query.
   *
   * @return    MapFilter_TreePattern_Tree_Attribute
   */
  public function setQuery ( $query ) {
  
    $this->query = $query;
    return $this;
  }

  /**
   * Determine whether an attribute is present in query.
   *
   * @since     0.5.4
   *
   * @param     Array           $query
   *
   * @return    Bool
   */
  public function isPresent () {
  
  }
  
  /**
   * Determine whether a value is valid.
   *
   * @since     0.5.4
   *
   * @param     Array           $query
   *
   * @return    Bool
   */
  public function isValid () {
  
  }

  /**
   * Convert attribute to string.
   *
   * @since     0.5.4
   *
   * @return    String          String representation of node.
   */
  public function __toString () {
  
    return (String) $this->value;
  }
}
