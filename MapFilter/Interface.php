<?php
/**
 * A MapFilter Interface
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
 
 */

/**
 * A MapFilter Interface
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5
 */
interface MapFilter_Interface {

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
   * All parsing is done just in time (however it can be triggered manually using
   * MapFilter::parse()) when some of parsing results is accessed (in this case
   * when MapFilter::getResults() is called for the first time):
   *
   * @clip{User/TreePattern/Duration.test.php,testDuration}
   *
   * @see       setPattern(), setQuery(), MapFilter_Pattern
   */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL, $query = Array ()
  );

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
  public function setPattern ( MapFilter_Pattern_Interface $pattern );
  
  /**
   * Set a query to filter.
   *
   * @since     0.1
   *
   * @param     Array|ArrayAccess              $query           A query to set
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
  public function setQuery ( $query );
  
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
  public function fetchResult ();
  
  /**
   * Get results.
   *
   * @since     0.2
   *
   * @return    Array|ArrayAccess               Parsing results.
   *
   * Get parsed query from latest parsing process.
   *
   * @see       fetchResult()
   */
  public function getResults ();
  
  /**
   * Get validation assertions.
   *
   * @since     0.4
   *
   * @return    Array|ArrayAccess               Parsing asserts.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @see       fetchResult()
   */
  public function getAsserts ();
  
  /**
   * Get flags.
   *
   * @since     0.4
   *
   * @return    Array|ArrayAccess               Parsing flags.
   *
   * Return flags that was sat during latest parsing process.
   *
   * @see       fetchResult()
   */
  public function getFlags ();
}
