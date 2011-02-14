<?php
/**
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
 * @since    $NEXT$
 */

/**
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Xml_InvalidXmlContentException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class
    MapFilter_TreePattern_Xml_InvalidXmlContentException
extends
    UnexpectedValueException
{

  public function __construct (
      $message = "Node '%s' has no content.",
      $code = 0,
      Exception $previous = NULL
  ) {
  
    parent::__construct ( $message, $code, $previous );
  }
  
  public function setNodeName ( $name ) {
  
    assert ( is_string ( $name ) );
    
    $this->message = sprintf ( $this->message, $name );
    
    return $this;
  }
}
