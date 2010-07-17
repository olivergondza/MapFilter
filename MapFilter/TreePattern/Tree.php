<?php
/**
 * Abstract Pattern node; Ancestor of all pattern nodes.
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
 * @since    0.3
 */

/**
 * @file        MapFilter/TreePattern/Tree/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Exception.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Interface.php' );

/**
 * Internal pattern tree.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.3
 */
abstract class MapFilter_TreePattern_Tree implements
    MapFilter_TreePattern_Tree_Interface
{

  /**
   * Tree pattern reference.
   *
   * @since     0.5.3
   *
   * @var       MapFilter_TreePattern_Interface         $_pattern
   */
  private $_pattern = NULL;
  
  /**
   * Determine whether the node was already satisfied.
   *
   * @since     0.3
   *
   * @var       Bool            $satisfied
   */
  protected $satisfied = FALSE;
  
  /**
   * Key-Attr value filter.
   *
   * @since     0.3
   *
   * @var       String          $_valueFilter
   */
  private $_valueFilter = NULL;
  
  /**
   * Node flag.
   *
   * @since     0.3
   *
   * @var       String          $flag
   */
  protected $flag = NULL;
  
  /**
   * Node assert.
   *
   * @since     0.3
   *
   * @var       String          $assert
   */
  protected $assert = NULL;
  
  /**
   * Node content.
   *
   * @since     0.5.2
   *
   * @var       Array           $content
   */
  protected $content = Array ();
  
  /**
   * Attache pattern.
   *
   * @since     0.5.3
   *
   * @var       String          $attachPattern
   */
  protected $attachPattern = NULL;
  
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
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $attribute )
    );
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
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $default )
    );
  }
  
  /**
   * Set content.
   *
   * A Fluent Method.
   *
   * @since     0.4
   *
   * @param     Array           $content        A content to set.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *    A pattern with new content.
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setContent ( Array $content ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_CONTENT
    );
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
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $valuePattern )
    );
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
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $iterator )
    );
  }

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
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setValueFilter ( $valueFilter ) {

    assert ( is_string ( $valueFilter ) );

    $this->_valueFilter = $valueFilter;
    return $this;
  }
  
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
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setFlag ( $flag ) {
  
    assert ( is_string ( $flag ) );
  
    $this->flag = $flag;
    return $this;
  }
  
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
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAssert ( $assert ) {
  
    assert ( is_string ( $assert ) );
  
    $this->assert = $assert;
    return $this;
  }
  
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
   * @throws    MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
   */
  public function setAttachPattern ( $attachPattern ) {
  
    assert ( is_string ( $attachPattern ) );
    
    $this->attachPattern = $attachPattern;
    return $this;
  }
  
  /**
   * Get valueFilter.
   *
   * @since     0.3
   *
   * @return    String          Node value filter.
   */
  protected function getValueFilter () {
  
    return (String) $this->_valueFilter;
  }
  
  /**
   * Get node followers reference.
   *
   * @since     0.3
   *
   * @return    Array           Node content reference.
   */
  public function &getContent () {
  
    $this->attachPattern ();
  
    return $this->content;
  }
  
  /**
   * Set TreePattern.
   *
   * @since     0.5.3
   *
   * @param     MapFilter_TreePattern   $pattern        A pattern to set.
   *
   * @return    MapFilter_TreePattern
   */
  public function setTreePattern ( MapFilter_TreePattern $pattern ) {
  
    $this->_pattern = $pattern;
    
    foreach ( $this->content as $follower ) {
    
      $follower->setTreePattern ( $pattern );
    }
    
    return $this;
  }
  
  /**
   * Create new tree instance.
   *
   * @since     0.3
   *
   * Setting is done by Fluent Methods.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   *
   * @see       setAssert(), setFlag(), setValueFilter(), setValuePattern(),
   *            setContent(), setDefault() or setAttribute()
   */
  public function __construct () {}
  
  /**
  * Set assertion value.
  *
  * @since      0.5.2
  *
  * @param      Array           $asserts
  * @param      Mixed           $assertValue            An assert value to set.
  *
  * @return     NULL
  */
  protected function setAssertValue ( Array &$asserts, $assertValue = Array () ) {
  
    if ( $this->assert === NULL ) {
    
      return;
    }
    
    $asserts[ $this->assert ] = (
         $assertValue === Array () || $assertValue === NULL
    )
        ? $this->assert
        : $assertValue;
  }
  
  /**
   * Determine whether the node is satisfied.
   *
   * @since     0.4
   *
   * @return    Bool            Satisfied or not.
   */
  protected function isSatisfied () {
  
    return $this->satisfied;
  }
  
  /**
   * Test whether an argument is present in the query.
   *
   * @since     0.4
   *
   * @param     String                  $attrName       Name of an attribute.
   * @param     Array|ArrayAccess       $query          Input array.
   *
   * @return    Bool                    Attribute present or not.
   */
  protected static function attrPresent ( $attrName, $query ) {
    
    assert ( is_string ( $attrName ) );
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
    
    return array_key_exists (
        $attrName,
        $query
    );
  }
  
  /** @cond     INTERNAL */
  
  /**
   * Filter boundaries.
   *
   * @since     0.3
   *
   * A format string to enclose the pattern with begin and end mark to ensure
   * that the strings are completely (not partially) equal. 
   */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /**
   * PCRE filter delimiter.
   *
   * @since     0.3
   * 
   * Special char to enclose PCRE filter.
   */
  const FILTER_DELIMITER = '/';
  
  /** @endcond */
  
  /**
   * Test whether a ForValue condition on tree node fits given pattern.
   *
   * @since     0.3
   *
   * @param     Mixed           $valueCandidate 	A value to fit.
   * @param     String|NULL     $pattern                Value pattern.
   *
   * @return    Bool            Does the value fit.
   */
  protected function valueFits ( $valueCandidate, $pattern ) {

    if ( $pattern === NULL ) {

      return TRUE;
    }

    /** Sanitize inputted PCRE */
    $valueCandidate = preg_quote (
        $valueCandidate,
        self::FILTER_DELIMITER
    );
  
    $pattern = sprintf (
        self::FILTER_BOUNDARIES,
        $pattern
    );

    $matchCount = preg_match (
        $pattern,
        $valueCandidate
    );

    /** Assumed match count is 1 (Equals) or 0 (Differs) */
    assert ( $matchCount < 2 );
    
    return (Bool) $matchCount;
  }
  
  /**
   * Actually attach a side pattern if needed.
   *
   * @since     0.5.3
   *
   * @return    NULL
   */
  protected function attachPattern () {
  
    if ( $this->attachPattern === NULL ) return;
    
    if ( $this->content !== Array () ) return;

    $this->content = Array (
        $this->_pattern->getSidePattern ( $this->attachPattern )
    );
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
   * Clone node followers.
   *
   * @note This method uses deep cloning.
   *
   * @since     0.3
   *
   * @return    MapFilter_TreePattern_Tree
   */
  public function __clone () {
  
    foreach ( $this->content as &$follower ) {

      $follower = clone ( $follower );
    }
  }
}

