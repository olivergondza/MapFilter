<?php
/**
 * Pattern matcher.
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
 * @since    $NEXT$
 */

/**
 * Pattern Matcher.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Matcher
 * @author   Oliver Gondza <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Tree_Matcher {

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
      '/^
        ([^\\\\\s\da-zA-Z<>\{\}\(\)])   # Posible delimiters
        (?:(?!(?<!\\\\)\1).)*           # Delimiter has to be escaped
        (\1)
        [imsxeADSUXu]*
      $/x'
  ;

  /**
   * Search RegExp.
   *
   * @since     $NEXT$
   *
   * @var       String          $_pattern
   */
  private $_pattern = NULL;

  /**
   * Create Matcher
   *
   * @since     $NEXT$
   *
   * @param     String          $pattern        A pattern to match if any.
   */
  public function __construct ( $pattern = NULL) {
  
    assert ( is_string ( $pattern ) || is_null ( $pattern ) );
    
    if ( $pattern === NULL ) return;

    $sanitized = preg_match ( self::SANITIZED_REGEXP, $pattern );
    
    if ( !$sanitized ) {
    
      $pattern = str_replace ( '/', '\/', $pattern );
      $pattern = sprintf ( self::REGEXP_BOUNDARIES, $pattern );
    }

    $this->_pattern = $pattern;
  }
  
  /**
   * Match given subject against pattern if there is any.
   *
   * @param     String          $subject
   *
   * @return    Bool
   */
  public function match ( $subject ) {
  
    if ( $this->_pattern === NULL ) return TRUE;

    assert ( is_string ( $subject ) );
    
    return (Bool) preg_match ( $this->_pattern, $subject );
  }
}