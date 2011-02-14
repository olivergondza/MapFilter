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
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */

/**
 * MapFilter_TreePattern_Tree Interface.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
interface MapFilter_TreePattern_Tree_Interface {
  
  /**
   * Get node followers reference.
   *
   * @since     0.4
   *
   * @return    Array           Node content reference.
   */
  public function &getContent ();
  
  /**
   * Set valueFilter.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $valueFilter    A valueFilter to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    New pattern with valueFilter.
   */
  public function setValueFilter ( $valueFilter );
  
  /**
   * Set Flag.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $flag           A flag to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    New pattern with flag.
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
   * @return    MapFilter_TreePattern_Tree_Interface
   *    New pattern with flag.
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
   * @return    MapFilter_TreePattern_Tree_Interface
   *    New pattern with attachPattern.
   */
  public function setAttachPattern ( $attachPattern );
  
  /**
   * Set TreePattern.
   *
   * @since     0.5.3
   *
   * @param     MapFilter_TreePattern   $pattern        A pattern to set.
   *
   * @return    MapFilter_TreePattern
   */
  public function setTreePattern ( MapFilter_TreePattern $pattern );
  
  /**
   * Create new tree instance.
   *
   * @since     0.4
   *
   * Setting is done by Fluent Methods.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *
   * @see       setAssert(), setFlag(), setValueFilter(), setValuePattern(),
   *            setContent(), setDefault() or setAttribute()
   */
  public function __construct ();
  
  /**
   * Make copy of the node.
   *
   * @since     0.4
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   */
  public function __clone ();
  
  /**
   * Satisfy certain node type and let its followers to get satisfied.
   *
   * @since     0.4
   *
   * @param     Array|ArrayAccess       &$query         A query to filter.
   * @param     Array                   &$asserts       Asserts.
   *
   * @return    Bool                    Satisfied or not.
   */
  public function satisfy ( &$query, Array &$asserts );
  
  /**
   * Pick-up satisfaction results.
   *
   * @since     0.3
   *
   * @param     Array           $result
   *
   * @return    Array
   */
  public function pickUp ( Array $result );
  
  /**
   * Get filtering flags.
   *
   * @since     0.5.1
   *
   * @param     Array           $flags
   *
   * @return    Array
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
  /**@}*/
}
