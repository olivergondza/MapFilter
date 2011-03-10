<?php
/**
 * Class to provide generic filter interface.
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
 * @since    0.1
 */

require_once 'PHP/MapFilter/Interface.php';

require_once 'PHP/MapFilter/NullPattern.php';

/**
 * Class to provide generic filter interface.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.1
 */
class MapFilter implements MapFilter_Interface {

  /**
   * Pattern.
   *
   * @since     0.4
   *
   * @var       MapFilter_PatternInterface     $_pattern
   * @see       setPattern(), __construct()
   */
  private $_pattern = NULL;
  
  /**
   * A Used Pattern.
   *
   * @since     0.5
   *
   * @var       MapFilter_PatternInterface     $_usedPattern
   * @see       setPattern(), __construct()
   */
  private $_usedPattern = NULL;

  /**
   * Read data / Query candidate.
   *
   * @since     0.4
   *
   * @var       Mixed                   $_query
   * @see       setQuery(), __construct()
   */
  private $_query = Array ();
  
  /**
   * Determine whether the filter configuration has been filtered.
   *
   * @since     0.4
   *
   * @var       Bool                    $_filtered
   * @see       _filter(), setQuery(), setPattern()
   */
  private $_filtered = FALSE;
  
  /**
   * Create new filter instance.
   *
   * @since     0.1
   *
   * @param     MapFilter_PatternInterface      $pattern        A pattern to set.
   * @param     Mixed                           $query	        A query to filter.
   *
   * @return    MapFilter_Interface
   *
   * If no pattern specified an untouched query will be returned:
   *
   * @clip{User/MapFilter.test.php,testEmptyPattern}
   *
   * @see       setPattern(), setQuery(), MapFilter_PatternInterface
   */
  public function __construct (
      MapFilter_PatternInterface $pattern = NULL,
      $query = Array ()
  ) {

    if ( $pattern === NULL ) {
    
      $pattern = new MapFilter_NullPattern ();
    }
    
    $this->setPattern ( $pattern );
    $this->setQuery ( $query );
  }

  /**
   * Set desired query pattern.
   *
   * Fluent Method
   *
   * @since     0.1
   *
   * @param     MapFilter_PatternInterface      $pattern        A pattern to set
   *
   * @return    MapFilter_Interface             Instance of MapFilter with new pattern.
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{Unit/MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setPattern ( MapFilter_PatternInterface $pattern ) {

    $this->_filtered = FALSE;

    $this->_pattern = clone $pattern;
    return $this;
  }
  
  /**
   * Set a query to filter.
   *
   * @since     0.1
   *
   * @param     Mixed                           $query           A query to set
   *
   * @return    MapFilter_Interface             Instance of MapFilter with new query.
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{Unit/MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setQuery ( $query ) {
  
    $this->_filtered = FALSE;
  
    $this->_query = $query;
    return $this;
  }
  
  /**
   * Parse filter configuration.
   *
   * @since     0.5
   *
   * @see       fetchResult(), getResults(), getAsserts(), getFlags()
   */
  private function _filter () {
  
    if ( $this->_filtered ) return;
  
    $this->_filtered = TRUE;
  
    $this->_usedPattern = clone $this->_pattern;
    
    $this->_usedPattern->parse ( $this->_query );
  }
  
  /**
   * Get full filtering results.
   *
   * @since     0.5
   *
   * @return    MapFilter_PatternInterface      Parsing results
   *
   * Return recently used pattern to obtain all kind of results to enable
   * user interface usage.
   *
   * @see       __construct(), setPattern()
   */
  public function fetchResult () {
  
    $this->_filter ();
  
    return $this->_usedPattern;
  }
  
  /**
   * Get results.
   *
   * @deprecated        Since 0.6.0
   * @since     0.2
   *
   * Equivalent to MapFilter_TreePattern_ResultInterface::getResults()
   *
   * @return    Mixed                           Parsing results.
   *
   * Get parsed query from latest parsing process.
   *
   * @see       fetchResult(), MapFilter_TreePattern_ResultInterface::getResults()
   */
  public function getResults () {

    trigger_error (
        'Accessing pattern results via MapFilter methods is deprecated',
        E_USER_NOTICE
    );

    return $this->fetchResult ()->getResults ();
  }
  
  /**
   * Get validation assertions.
   *
   * @deprecated        Since 0.6.0
   * @since     0.4
   *
   * Equivalent to MapFilter_TreePattern_AssertInterface::getAsserts()
   *
   * @return    MapFilter_TreePattern_Asserts   Parsing asserts.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @see       fetchResult(), MapFilter_TreePattern_AssertInterface::getAsserts()
   */
  public function getAsserts () {
  
    trigger_error (
        'Accessing pattern results via MapFilter methods is deprecated',
        E_USER_NOTICE
    );
  
    return $this->fetchResult ()->getAsserts ();
  }
  
  /**
   * Get flags.
   *
   * @deprecated        Since 0.6.0
   * @since     0.4
   *
   * Equivalent to MapFilter_TreePattern_FlagInterface::getFlags()
   *
   * @return    MapFilter_TreePattern_Flags     Parsing flags.
   *
   * Return flags that was sat during latest parsing process.
   *
   * @see       fetchResult(), MapFilter_TreePattern_FlagInterface::getFlags()
   */
  public function getFlags () {
  
    trigger_error (
        'Accessing pattern results via MapFilter methods is deprecated',
        E_USER_NOTICE
    );
  
    return $this->fetchResult ()->getFlags();
  }
}
