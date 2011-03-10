<?php
/**
 * Class to handle invalid structure exception.
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
 * @since    0.6.0
 */

/**
 * Class to handle invalid structure exception.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_InvalidStructureException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.6.0
 */
class
    MapFilter_InvalidStructureException
extends
    UnexpectedValueException
{

    /**
     * Create excpetion
     *
     * @param String    $message  Exception message.
     * @param Int       $code     Exception code.
     * @param Exception $previous Previous exception.
     */
    public function __construct(
        $message = 'Data structure passed as a query can not be parsed using given pattern.',
        $code = 0,
        Exception $previous = null
    ) {
    
        parent::__construct($message, $code, $previous);
    }
}
