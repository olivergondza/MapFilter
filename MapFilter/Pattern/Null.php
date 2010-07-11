<?php
/**
 * MapFilter Null pattern.
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
 * @since    0.5
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * MapFilter Pattern.
 *
 * @file        MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Interface.php' );

/**
 * @file        MapFilter/Pattern/AssertInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/AssertInterface.php' );

/**
 * @file        MapFilter/Pattern/FlagInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/FlagInterface.php' );

/**
 * @file        MapFilter/Pattern/ResultInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/ResultInterface.php' );

/**
 * A mock implementation of basic MapFilter_Pattern interfaces.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_Pattern_Null
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.5
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
class MapFilter_Pattern_Null implements
    MapFilter_Pattern_AssertInterface,
    MapFilter_Pattern_FlagInterface,
    MapFilter_Pattern_ResultInterface
{

  /**
   * A parsed query to return by getResults().
   *
   * @since     0.5
   *
   * @var       Array|ArrayAccess       $_query
   */
  private $_query = Array ();

  /**
   * An empty constructor.
   *
   * @since     0.5
   *
   * @return    MapFilter_Pattern_Null
   */
  public function __construct () {}

  /**
   * @copyfull{MapFilter_Pattern_ResultInterface::getResults()}
   */
  public function getResults () {
  
    return $this->_query;
  }
  
  /**
   * @copyfull{MapFilter_Pattern_AssertInterface::getAsserts()}
   */
  public function getAsserts () {
    
    return Array ();
  }
  
  /**
   * @copyfull{MapFilter_Pattern_FlagInterface::getFlags()}
   */
  public function getFlags () {
  
    return Array ();
  }
  
  /**
   * @copyfull{MapFilter_Pattern_Interface::parse()}
   */
  public function parse ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
  
    $this->_query = $query;
  }
}