#!/usr/bin/php
<?php
/** 
 * Doxygen PHP compatibility filter.
 *
 * PHP Version 5.1.0
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *                            
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * A simple filter meant to facilitate documentation generation for PHP.
 *
 * - Brings @@param tag compatibility
 * - Handles un-existing tags or tags that acts differently.
 *
 * @note        Code produced by this filter is intended for doxygen input,
 * not to run.
 * 
 * Usage:
 *
 * Put
 *
 * INPUT_FILTER = %phpCompatibilityFilter.php
 *
 * or
 *
 * FILTER_PATTERNS = *.php=phpCompatibilityFilter.php
 *
 * into doxygen.ini file. All files (that match given pattern) will be
 * parsed by this filter.
 *
 * @file    phpCompatibilityFilter.php
 *
 * @author  Oliver Gondža <324706@mail.muni.cz>
 * @license http://www.gnu.org/copyleft/lesser.html  LGPL License
 */

/**
 * Copy type from @@param and @@return annotation to function header.
 *
 * Turns
 *
 * @code
 * // @param    int     $a      My int
 * // @param    string  $s      My string
 * // @return   bool    My result
 * //
 * function func ( $a, $s ) { ... }
 * @endcode
 *
 * into:
 *
 * @code
 * // @param    $a      My int
 * // @param    $s      My string
 * // @return   My result
 * //
 * function bool func ( int $a, string $s ) { ... }
 * @endcode
 *
 * All methods or functions with header to fix must have own @@param and
 * @@return annotations.
 *
 * @warning     Does not work with copied @@param/@@return tags (@@copydoc,
 * @@copybrief, @@copydetails) and with inherited tags (INHERIT_DOCS). 
 * Since the original method can lie in different file so tags are
 * unavailable.
 * 
 * @param       Array   $data   Lines of original file
 *
 * @return      Array   Array of result
 */ 
function fix_function_header ( Array $data ) {

  $params = Array ();
  $return = '';
  
  $prevLine = '';
  foreach ( $data as &$line ) {

    /**
     * Find param types
     */
    $fullline = $prevLine . substr ( $line, strpos ( $line, '* ' ) + 2 );
    $match = preg_match (
        '/(:?\\\|@)param\s*(?P<type>\S+)\s*(?P<name>\S+)/',
        $fullline,
        $matches
    );
    
    if ( $match ) {
    
      if (
          !array_key_exists ( 'type', $matches )
          && !array_key_exists ( 'name', $matches )
      ) {
      
        $prevLine = $fullline;
      } else {

        $allowed = preg_match (
            '/^(boolean|bool|integer|int|float|double|string|object|resource'
            . '|mixed|number|callback|\S+\|\S+|\S+,\S+)$/i',
            $matches[ 'type' ]
        );

        if ( $allowed ) {

          $params[ $matches[ 'name' ] ] = $matches[ 'type' ];
        }
        
        $prevLine = '';
      }

      
      continue;
    }

    /**
     * Find return type
     */
    $fullline = $prevLine . substr ( $line, strpos ( $line, '* ' ) + 2 );
    $match = preg_match (
        '/(:?\\\|@)return\s*(?P<type>\S+)/',
        $fullline,
        $matches
    );
    
    if ( $match ) {
    
      if ( !array_key_exists ( 'type', $matches ) ) {
      
        $prevLine = $fullline;
      } else {
      
        $return = $matches[ 'type' ];
        $prevLine = '';
      }

      continue;
    }

    /**
     * Modify header
     */
    $token = $inFunction = preg_match (
        '/ function\s+\S+/',
        $fullline
    );
    
    if ( $inFunction ) {
    
      if ( $token ) {
    
        $line = str_replace ( 'function', "$return function", $line );
        $return = '';
      }

      foreach ( $params as $paramName => $paramType ) {
       
        $line = str_replace ( $paramName, "$paramType $paramName", $line );
      }
      $params = Array ();
    }

    /**
     * Prepare for new process
     */
    if ( is_int ( strpos ( ')', $line ) ) ) {
    
      $inFunction = FALSE;
    }
  }
  
  return $data;
}

/**
 * Place @@file tag on the first row in the first comment in all files.
 *
 * @param       Array   $data           Lines of input 
 *
 * @return      Array   Array of result
 */
function addFileTag ( Array $data ) {

  $inserted = FALSE;
  foreach ( $data as &$line ) {
    $line = preg_replace ( 
        '/(\/\*\*)/', '\\1 @file', $line, 1, $inserted
    );

    if ( $inserted == TRUE ) return $data;
  } 
  
  return $data;
}

/** Read file data */
$data = file ( $argv[ 1 ] );

/**
 * Copy type annotation from @@param tag to function header.
 *
 * PHP disallow type hinting of scalar values in type header. On the other
 * hand doxygen mandate this behavior to generate correct documentation.
 */
//$data = fix_function_header ( $data );

/**
 * Add @@file tag.
 *
 * Tell doxygen that the first docBlock in file belongs to the file in witch
 * it is placed.
 */
$data = addFileTag ( $data );

/**
 * Substitute @@license tag for paragraph titled License and tag content.
 * 
 * There is no @@license tag in doxygen commands. This is simple replacement
 * for it.
 */
$data = preg_replace (
    '/\*\s(\\\|@)license\s*(\S+)\s*(.*)/',
    "\\1par License: \n      \\2 \\3",
    $data
);

/**
 * Substitute @@package tag for paragraph titled Package and tag content.
 *
 * A @@package tag is usable only in java.
 */
$data = preg_replace (
    '/\*\s(\\\|@)package\s*(\S+)/',
    "\\1par Package: \n     %\\2",
    $data
);

/**
 * Substitute @@category tag for paragraph titled Category and the tag content.
 *
 * A @@category tag does exist in doxygen but it is used for Objective-C only.
 */
$data = preg_replace (
    '/\*\s(\\\|@)category\s*(\S+)/',
    "\\1par Category: \n     %\\2",
    $data
);

/**
 * Enclose @@link tag with @@endlink
 *
 * A @@link tag does exists in doxygen but is must be enclosed by @@endlink
 * tag.
 */
$data = preg_replace (
    '/\*\s((\\\|@)link\s+\S+)/',
    "\\2par URL: \n    \\1 \\2endlink",
    $data
);

/**
 * Strip type annotation from @@param tag
 *
 * A type annotation in @@param tag (mandatory in php) is prohibited in
 * doxygen.
 */
$data = preg_replace (
    '/\*\s(\\\|@)param\s+\S+(.*)/',
    "* \\1param \\2",
    $data
);

/**
 * Strip type annotation from @@return tag
 *
 * A type annotation in @@return tag (mandatory in php) is prohibited in
 * doxygen.
 */
$data = preg_replace ( '/\*\s(\\\|@)return\s+\S+/', "* \\1return", $data );

echo implode ( $data );
