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
   * @since     $NEXT$
   *
   * @var       String          $existenceAssert
   */
  protected $existenceAssert = NULL;
  
  /**
   * Node assert.
   *
   * @since     $NEXT$
   *
   * @var       String          $validationAssert
   */
  protected $validationAssert = NULL;
  
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
   * Node setters
   *
   * @since     $NEXT$
   *
   * @vat       Array           $setters
   */
  protected $setters = Array ();
  
  /**
   * Create new tree instance.
   *
   * @since     0.3
   *
   * Setting is done by Fluent Methods.
   *
   * @return    MapFilter_TreePattern_Tree_Interface
   */
  public function __construct () {
  
    $this->setSetters ( Array (
        'flag' => 'setFlag',
        'assert' => 'setAssert',
        'attachPattern' => 'setAttachPattern',
        'forValue' => 'setValueFilter',
    ) );
  }
  
  /**
   * Set setter methods alloved for node.
   *
   * @since     $NEXT$
   *
   * @param     Array   $setters        Array   of attrs and its setters.
   */
  protected function setSetters ( Array $setters ) {
  
    $this->setters += $setters;
    return $this;
  }
  
  /**
   * Get alloved setters.
   *
   * @since     $NEXT$
   *
   * @return    Array
   */
  public function getSetters () {
  
    return $this->setters;
  }
  
  /**
   * Set Flag.
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
  
    $this->existenceAssert = $this->validationAssert = $assert;
    return $this;
  }
  
  /**
   * Set attachPattern.
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
   * Set valueFilter.
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

    $this->_valueFilter = self::sanitizeRegExp ( $valueFilter );

    return $this;
  }

  /**
   * RegExp boundaries.
   *
   * @since     0.3
   *
   * A format string to enclose the RegExp with begin and end mark to ensure
   * that the strings are completely (not partially) equal.
   */
  const REGEXP_BOUNDARIES = '/^%s$/';

  /**
   * Is RegExp sanitized already.
   *
   * @since     $NEXT$
   *
   * Determine whether a string is sanitized regexp
   */
  const SANITIZED_REGEXP =
      '/^([^\\\\\s\da-zA-Z<>\{\}\(\)]).*(\1)[imsxeADSUXu]*$/'
  ;

  /**
   * Sanitize RegExp
   *
   * @since     $NEXT$
   *
   * @param     String          $regexp
   *
   * @return    String
   */
  public static function sanitizeRegExp ( $regexp ) {

    assert ( is_string ( $regexp ) );

    $sanitized = preg_match ( self::SANITIZED_REGEXP, $regexp );
    
    if ( $sanitized ) {
    
      return $regexp;
    }

    $regexp = str_replace ( '/', '\/', $regexp );

    return sprintf ( self::REGEXP_BOUNDARIES, $regexp );
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
   * Set assertion value.
   *
   * @since      0.5.2
   *
   * @param      Array           $asserts
   * @param      Mixed           $assertValue            An assert value to set.
   */
  protected function setAssertValue (
      Array &$asserts, $assertValue = Array ()
  ) {
  
    if ( $assertValue === Array () ) {

       $assertToSet = $this->existenceAssert;
       $valueToSet = $this->existenceAssert;
    } else {

       $assertToSet = $this->validationAssert;
       $valueToSet = $assertValue;
    }
  
    if ( $assertToSet === NULL ) return;
    
    $asserts[ $assertToSet ] = $valueToSet;
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

