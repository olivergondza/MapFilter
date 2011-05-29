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
     * A parsed query to return by getResults().
     *
     * @since 0.5
     *
     * @var Mixed $_query
     */
    private $_query = Array();

    /**
     * An empty constructor.
     *
     * @return    MapFilter_Pattern_Null
     *
     * @since 0.5
     */
    public function __construct()
    {
    }

    /**
     * Get results.
     *
     * Get parsed query from latest parsing process.
     *
     * @return Mixed Parsing results.
     *
     * @since 0.5
     */
    public function getResults()
    {
    
        return $this->_query;
    }
    
    /**
     * Parse the given query against the pattern.
     *
     * @param Mixed $query A user query.
     *
     * @return null
     *
     * @since     0.5
     */
    public function parse($query)
    {
    
        $this->_query = $query;
    }
}
