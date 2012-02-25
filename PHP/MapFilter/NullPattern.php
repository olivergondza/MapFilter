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
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5
 */

require_once 'PHP/MapFilter/PatternInterface.php';

/**
 * A mock implementation of basic MapFilter_PatternInterface interface.
 *
 * Pattern performs _no_ opperation at all on user query and return it as is.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_NullPattern
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5
 */
class MapFilter_NullPattern implements MapFilter_PatternInterface
{

    /**
     * An empty constructor.
     *
     * @return    MapFilter_NullPattern
     *
     * @since 0.5
     */
    public function __construct()
    {
    }

    /**
     * Parse the given query against the pattern.
     *
     * @param Mixed $query A user query.
     *
     * @return NullPattern_Result
     *
     * @since     0.5
     */
    public function parse($query)
    {
    
        return $query;
    }
}
