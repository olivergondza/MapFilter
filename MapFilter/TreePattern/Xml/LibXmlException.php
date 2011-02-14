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
 * @class    MapFilter_TreePattern_Xml_LibXmlException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
abstract class
    MapFilter_TreePattern_Xml_LibXmlException
extends
    UnexpectedValueException
{

  /**
   * LibXml error to Exception mapping.
   *
   * @since     0.1
   *
   * @var       Array           $_errorToException
   */
  private static $_errorToException = Array (
      LIBXML_ERR_WARNING => 'MapFilter_TreePattern_Xml_LibXmlWarningException',
      LIBXML_ERR_ERROR   => 'MapFilter_TreePattern_Xml_LibXmlErrorException',
      LIBXML_ERR_FATAL   => 'MapFilter_TreePattern_Xml_libXmlFatalException',
  );
  
  /**
   * LibXml error to Message.
   *
   * @since     $NEXT$
   *
   * @var       Array           $_errorToMessage
   */
  private static $_errorToMessage = Array (
      LIBXML_ERR_WARNING => 'LibXML warning: %s on line %s (in file %s).',
      LIBXML_ERR_ERROR   => 'LibXML error: %s on line %s (in file %s).',
      LIBXML_ERR_FATAL   => 'LibXML fatal: %s on line %s (in file %s).',
  );

  /**
   * Wrap error into exception.
   *
   * @param     libXMLError     $error          An error to wrap.
   *
   * @param     MapFilter_TreePattern_Xml_LibXmlException       $error
   *
   * @return    MapFilter_TreePattern_Xml_LibXmlException
   */
  public static function wrap ( libXMLError $error ) {
  
    $class = self::$_errorToException[ $error->level ];

    $message = sprintf (
        self::$_errorToMessage[ $error->level ],
        $error->message, $error->line, $error->file
    );
    
    return new $class ( $message );
  }
}

/**
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Xml_LibXmlWarningException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Xml_LibXmlWarningException extends MapFilter_TreePattern_Xml_LibXmlException {}

/**
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Xml_LibXmlErrorException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Xml_LibXmlErrorException extends MapFilter_TreePattern_Xml_LibXmlException {}

/**
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Xml_LibXmlFatalException
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
class MapFilter_TreePattern_Xml_LibXmlFatalException extends MapFilter_TreePattern_Xml_LibXmlException {}
