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

/**
 * MapFilter Interface.
 *
 * @file                MapFilter/Interface.php
 */
require_once dirname ( __FILE__ ) . '/MapFilter/Interface.php';

/**
 * MapFilter Null Pattern.
 *
 * @file                MapFilter/Pattern/Null.php
 */
require_once dirname ( __FILE__ ) . '/MapFilter/Pattern/Null.php';

/**
 * Package Exceptions
 *
 * @file                MapFilter/Exception.php
 */
require_once dirname ( __FILE__ ) . '/MapFilter/Exception.php';

/**
 * Class to provide generic filter interface.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.1
 */
class MapFilter implements MapFilter_Interface {

  /**
   * Pattern.
   *
   * @since     0.4
   *
   * @var       MapFilter_Pattern_Interface     $_pattern
   * @see       setPattern(), __construct()
   */
  private $_pattern = NULL;
  
  /**
   * A Used Pattern.
   *
   * @since     0.5
   *
   * @var       MapFilter_Pattern_Interface     $_usedPattern
   * @see       setPattern(), __construct()
   */
  private $_usedPattern = NULL;

  /**
   * Read data / Query candidate.
   *
   * @since     0.4
   *
   * @var       Array                           $_query
   * @see       setQuery(), __construct()
   */
  private $_query = Array ();
  
  /**
   * Determine whether the filter configuration has been filtered.
   *
   * @since     0.4
   *
   * @var       Bool    $_filtered
   * @see       _filter(), setQuery(), setPattern()
   */
  private $_filtered = FALSE;
  
  /**
   * Create new filter instance.
   *
   * @since     0.1
   *
   * @param     MapFilter_Pattern_Interface     $pattern        A pattern to set.
   * @param     Array|ArrayAccess               $query	        A query to filter.
   *
   * @return    MapFilter_Interface
   *
   * If no pattern specified an untouched query will be returned:
   *
   * @clip{User/MapFilter.test.php,testEmptyPattern}
   *
   * All parsing is done just in time when some of parsing results is
   * accessed (in this case when MapFilter::getResults() is called for the
   * first time):
   *
   * @clip{User/TreePattern/Duration.test.php,testDuration}
   *
   * @see       setPattern(), setQuery(), MapFilter_Pattern
   */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL,
      $query = Array ()
  ) {

    assert ( is_array ( $query ) ||  ( $query instanceof ArrayAccess ) );
    
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
   * @param     MapFilter_Pattern_Interface     $pattern        A pattern to set
   *
   * @return    MapFilter       Instance of MapFilter with new pattern.
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{Unit/MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setPattern ( MapFilter_Pattern_Interface $pattern = NULL) {

    $this->_filtered = FALSE;

    $this->_pattern = ( $pattern === NULL )
        ? new MapFilter_Pattern_Null ()
        : clone ( $pattern );
    
    return $this;
  }
  
  /**
   * Set a query to filter.
   *
   * @since     0.1
   *
   * @param     Array|ArrayAccess       $query           A query to set
   *
   * @return    MapFilter               Instance of MapFilter with new query.
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{Unit/MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setQuery ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
  
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
   * @return    MapFilter_Pattern_Interface     Parsing results
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
   * @since     0.2
   *
   * Equivalent to MapFilter_Pattern_ResultInterface::getResults()
   *
   * @return    Array|ArrayAccess               Parsing results.
   *
   * Get parsed query from latest parsing process.
   *
   * @see       fetchResult(), MapFilter_Pattern_ResultInterface::getResults()
   */
  public function getResults () {

    return $this->fetchResult ()->getResults ();
  }
  
  /**
   * Get validation assertions.
   *
   * @since     0.4
   *
   * Equivalent to MapFilter_Pattern_AssertInterface::getAsserts()
   *
   * @return    Array|ArrayAccess               Parsing asserts.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @see       fetchResult(),MapFilter_Pattern_AssertInterface::getAsserts()
   */
  public function getAsserts () {
  
    return $this->fetchResult ()->getAsserts ();
  }
  
  /**
   * Get flags.
   *
   * @since     0.4
   *
   * Equivalent to MapFilter_Pattern_FlagInterface::getFlags()
   *
   * @return    Array|ArrayAccess               Parsing flags.
   *
   * Return flags that was sat during latest parsing process.
   *
   * @see       fetchResult(), MapFilter_Pattern_FlagInterface::getFlags()
   */
  public function getFlags () {
  
    return $this->fetchResult ()->getFlags();
  }
}
