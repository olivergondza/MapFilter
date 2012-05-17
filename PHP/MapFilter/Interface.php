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
 * @since    0.5
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
interface MapFilter_Interface
{

    /**
     * Create new filter instance.
     *
     * @param MapFilter_PatternInterface $pattern A pattern to set.
     * @param Mixed                      $query	  A query to filter.
     *
     * @return MapFilter_Interface
     *
     * If no pattern specified an untouched query will be returned:
     *
     * @snippet User/MapFilter.test.php testEmptyPattern
     *
     * @see setPattern(), setQuery(), MapFilter_Pattern
     *
     * @since 0.1
     */
    public function __construct(
        MapFilter_PatternInterface $pattern = null,
        $query = Array()
    );

    /**
     * Set desired query pattern.
     *
     * Fluent Method
     *
     * @param MapFilter_PatternInterface $pattern A pattern to set.
     *
     * @return MapFilter_Interface Instance of MapFilter with new pattern.
     *
     * MapFilter can be configured using both constructor and specialized fluent
     * methods setPattern() and setQuery():
     *
     * @snippet Unit/MapFilter.test.php testInvocation
     *
     * @see __construct(), setQuery()
     *
     * @since 0.1
     */
    public function setPattern(MapFilter_PatternInterface $pattern);

    /**
     * Set a query to filter.
     *
     * @param Mixed $query A query to set.
     *
     * @return MapFilter_Interface Instance of MapFilter with new query.
     *
     * MapFilter can be configured using both constructor and specialized fluent
     * methods setPattern() and setQuery():
     *
     * @snippet Unit/MapFilter.test.php testInvocation
     *
     * @see __construct(), setPattern()
     *
     * @since 0.1
     */
    public function setQuery($query);

    /**
     * Get full filtering results.
     *
     * @since 0.5
     *
     * @return MapFilter_PatternInterface Parsing results.
     *
     * Return recently used pattern to obtain all kind of results to enable
     * user interface usage.
     *
     * @see __construct(), setPattern()
     */
    public function fetchResult();
}
