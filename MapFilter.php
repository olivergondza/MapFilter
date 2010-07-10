<?php
/**
 * Class to filter key-value data structures.
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
 * @since    0.1
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * MapFilter Interface.
 *
 * @file                MapFilter/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/MapFilter/Interface.php' );

/**
 * MapFilter Null Pattern.
 *
 * @file                MapFilter/Pattern/Null.php
 */
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern/Null.php' );

/**
 * Package Exceptions
 *
 * @file                MapFilter/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/MapFilter/Exception.php' );

/**
 * Class to filter key-value data structures.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 *
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL
 * @since    0.1
 *
 * @link     http://github.com/olivergondza/MapFilter
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
   * @copyfull{MapFilter_Interface::__construct()}
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
   * @copyfull{MapFilter_Interface::setPattern()}
   */
  public function setPattern ( MapFilter_Pattern_Interface $pattern = NULL) {

    $this->_filtered = FALSE;

    $this->_pattern = ( $pattern === NULL )
        ? new MapFilter_Pattern_Null ()
        : clone ( $pattern );
    
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_Interface::setQuery()}
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
  
    if ( $this->_filtered ) {
      
      return;
    }
  
    $this->_filtered = TRUE;
  
    $this->_usedPattern = clone ( $this->_pattern );
    
    $this->_usedPattern->parse ( $this->_query );
  }
  
  /**
   * @copyfull{MapFilter_Interface::fetchResult()}
   */
  public function fetchResult () {
  
    $this->_filter ();
  
    return $this->_usedPattern;
  }
  
  /**
   * @copyfull{MapFilter_Interface::getResults()}
   */
  public function getResults () {

    return $this->fetchResult ()->getResults ();
  }
  
  /**
   * @copyfull{MapFilter_Interface::getAsserts()}
   */
  public function getAsserts () {
  
    return $this->fetchResult ()->getAsserts ();
  }
  
  /**
   * @copyfull{MapFilter_Interface::getFlags()}
   */
  public function getFlags () {
  
    return $this->fetchResult ()->getFlags();
  }
}
