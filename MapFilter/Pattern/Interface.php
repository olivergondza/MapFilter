<?php
/**
 * MapFilter_Pattern Interface.
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
 * @since    0.5
 */

/**
 * MapFilter_Pattern Interface.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_Pattern_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5
 */
interface MapFilter_Pattern_Interface {

  /**
   * Parse the given query against the pattern.
   *
   * @since     0.5
   *
   * @param     Array|ArrayAccess       $query          A user query.
   */
  public function parse ( $query );
}