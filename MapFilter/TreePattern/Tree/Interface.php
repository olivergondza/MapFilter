<?php
/**
 * MapFilter_TreePattern_Tree Interface.
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
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * MapFilter_TreePattern_Tree Interface.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
interface MapFilter_TreePattern_Tree_Interface {
  
  /**
   * Set attribute.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $attribute              An attribute to set.
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new attribute.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAttribute ( $attribute );
  
  /**
   * Set default value.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $default        A default value to set.
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new default value.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setDefault ( $default );
  
  /**
   * Set content.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     Array           $content        A content to set.
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new content.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setContent ( Array $content );
  
  /**
   * Set valueFilter.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $valuePattern   A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new valueFilter.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setValuePattern ( $valuePattern );

  /**
   * Set valueFilter.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $valueFilter    A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with valueFilter.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setValueFilter ( $valueFilter );
  
  /**
   * Set iterator.
   *
   * A Fluent Method.
   *
   * @since     0.5.2
   *
   * @param     String          $iterator       An iterator value to set.
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with iterator.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setIterator ( $iterator );
  
  /**
   * Set Flag.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $flag           A flag to set.
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with flag.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setFlag ( $flag );
  
  /**
   * Set Assert.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $assert         An assert to set.
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with flag.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAssert ( $assert );
  
  /**
   * Set attachPattern.
   *
   * A Fluent Method.
   *
   * @since     0.5.3
   *
   * @param     String          $attachPattern  A pattern name to attach.
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with attachPattern.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAttachPattern ( $attachPattern );
  
  /**
   * Set TreePattern.
   *
   * @since     0.5.3
   *
   * @return    MapFilter_TreePattern
   */
  public function setTreePattern ( MapFilter_TreePattern $pattern );
  
  /**
   * Create new tree instance.
   *
   * @since     0.4
   *
   * @return    MapFilter_TreePattern_Tree_Interface    New instance.
   */
  public function __construct ();
  
  /**
   * Make copy of the node.
   *
   * @since     0.4
   */
  public function __clone ();
  
  /**
   * Satisfy certain node type and let its followers to get satisfied.
   *
   * @since     0.4
   *
   * @param     Array|ArrayAccess       $query
   * @param     Array                   $asserts
   *
   * @return    Bool
   */
  public function satisfy ( &$query, Array &$asserts );
  
  /**
   * Pick-up satisfaction results.
   *
   * @since     0.4
   *
   * @param     Array           $result
   *
   * @return    Array           Results array
   */
  public function pickUp ( Array $result );
  
  /**
   * Get filtering flags.
   *
   * @since     0.5.1
   *
   * @param     Array           $flags		
   *
   * @return    Array           Flags array 
   */
  public function pickUpFlags ( Array $flags );
  
  /**
  * Possible iterator values.
  *
  * @since      0.5.2
  * @{
  */
  const ITERATOR_VALUE_NO = 'no';
  const ITERATOR_VALUE_YES = 'yes';
  const ITERATOR_VALUE_AUTO = 'auto';
  /**@}*/
}
