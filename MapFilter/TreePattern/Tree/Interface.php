<?php
/**
 * MapFilter_TreePattern_Tree Interface.
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
 * MapFilter_TreePattern_Tree Interface.
 *
 * @class       MapFilter_TreePattern_Tree_Interface
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
interface MapFilter_TreePattern_Tree_Interface {
  
  /**
   * Set attribute.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $attribute              An attribute to set
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new attribute
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
   * @param     String          $default        A default value to set
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new default value
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
   * @param     Array           $content        A content to set
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new content
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
   * @param     String          $valuePattern   A valueFilter to set
   *
   * @return    MapFilter_TreePattern_Tree      A pattern with new valueFilter
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
   * @param     String          $valueFilter    A valueFilter to set
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with valueFilter
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setValueFilter ( $valueFilter );
  
  /**
   * Set Flag.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     String          $flag           A flag to set
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with flag
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
   * @param     String          $assert         An assert to set
   *
   * @return    MapFilter_TreePattern_Tree      New pattern with flag
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAssert ( $assert );
  
  /**
   * Create new tree instance.
   *
   * @since     0.4
   *
   * @return    MapFilter_TreePattern_Tree_Interface    New instance
   */
  public function __construct ();
  
  /**
   * Make copy of the node
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
   * @param     Array           $results
   *
   * @return    Array           Results array
   */
  public function pickUp ( Array $results );
  
  /**
   * Get filtering flags
   *
   * @since     0.5.1
   *
   * @param     Array           $flags		
   *
   * @return    Array           Flags array 
   */
  public function pickUpFlags ( Array $flags );
}
