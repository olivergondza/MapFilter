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
 * @author  Oliver Gondža <324706@mail.muni.cz>
 * @license http://www.gnu.org/copyleft/lesser.html  LGPL License
 */

/**
 * Copy type from @@param, @@returnand @@throws/@@exception annotation to
 * function header.
 *
 * Turns
 *
 * @code
 * // @param    int     $a      My int
 * // @param    string  $s      My string
 * // @return   bool            My result
 * // @throws   exception       My exception
 * //
 * function &func ( $a, &$s ) { ... }
 * @endcode
 *
 * into:
 *
 * @code
 * // @param            $a      My int
 * // @param            $s      My string
 * // @return                   My result
 * // @throws   exception       My exception
 * //
 * function &bool func ( int $a, string &$s ) throw ( MyException ) { ... }
 * @endcode
 *
 * All methods or functions with header to fix must have own @@param,
 * @@return and @@throws/@@exception annotations.
 *
 * @warning     Does not work with copied @@param/@@return/@@throws tags
 * (@@copydoc, @@copydetails) and with inherited tags (INHERIT_DOCS).  Since
 * the original method can lie in different file so tags are unavailable.
 * 
 * @param       Array   $data   Lines of original file
 *
 * @return      Array   Array of result
 */ 
function fix_function_header ( Array $data ) {

  $params = Array ();
  $exceptions = Array ();
  $return = 'void';
  
  $prevLine = '';
  $inFunction = FALSE;
  foreach ( $data as &$line ) {

    /**
     * Find param types
     */
    $fullline = $prevLine . substr ( $line, strpos ( $line, '* ' ) + 2 );
    $match = preg_match (
        '/(?:\\\|@)param\s+(?P<type>\S+)\s+(?P<name>\S+)/',
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
        '/(?:\\\|@)return\s+(?P<type>\S+)/',
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
     * Find exception types
     */
    $match = preg_match (
        '/^\s*\*\s*(?:\\\|@)(?:throws|exception)\s+(?P<type>\S+)/',
        $line,
        $matches
    );

    if ( $match ) {

      if ( array_key_exists ( 'type', $matches ) ) {
      
        $exceptions[] = $matches[ 'type' ];
      }

      continue;
    }

    /**
     * Modify header
     */
    $inFunction |= $token = preg_match (
        '/function\s+\S+/',
        $line
    );

    if ( $inFunction ) {

      if ( $token ) {
    
        $line = preg_replace (
            '/^([^\*]*)function(\s+)(&)?/',
            "\\1function \\3$return\\2",
            $line,
            1
        );
        $return = 'void';
      }

      foreach ( $params as $paramName => $paramType ) {

        $line = str_replace ( $paramName, "$paramType $paramName", $line );
      }
    }

    /**
     * Flush exceptions
     */
    if ( is_int ( strpos ( $line, ')' ) ) ) {

      if ( $exceptions !== Array () ) {

        $exceptionString = sprintf (
            'throw ( %s )',
            implode ( ', ', $exceptions )
        );
        
        $line = str_replace ( ')', ") $exceptionString", $line);
        
        $exceptions = Array ();
      }
    
      $params = Array ();
      $inFunction = FALSE;
    }
    
  }
  
  return $data;
}

/**
 * Fix valiable/property declaration.
 *
 * Turns
 *
 * @code
 * // @var      Bool    $_myVar         My var
 * private $_myVar = FALSE;
 * @endcode
 *
 * into:
 *
 * @code
 * // @var      Bool    $_myVar         My var
 * private Bool $_myVar = FALSE;
 * @endcode
 *
 * @param       Array   $data   Input lines.
 *
 * @result      Array   Array of output.
 */
function fix_var_declaration ( $data ) {

  $name = '';
  $type = '';

  foreach ( $data as &$line ) {
  
    $match = preg_match (
        '/(\\\|@)var\s+(?P<type>\S+)\s+(?P<name>\S+)/',
        $line,
        $matches
    );
    
    if ( $match ) {
      
      $name = $matches[ 'name' ];
      $type = $matches[ 'type' ];
      
      continue;
    }

    $replaced = FALSE;
    if ( $name && $type ) {

      $quotedName = preg_quote ( $name );
      $line = preg_replace (
          "/^([^\*]*)$quotedName/",
          "\\1$type $name",
          $line,
          1,
          $replaced
      );

      if ( $replaced ) {
        $name = $type = '';
      }
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

/**
 * Validate input filename.
 */
if ( !array_key_exists ( 1, $argv ) ) {

  trigger_error ( 'No filename specified.', E_USER_ERROR );
} elseif ( !is_readable ( $argv[ 1 ] ) ) {

  trigger_error (
      sprintf (
          'Filename %s is not readable.',
          $argv[ 1 ]
      ),
      E_USER_ERROR
  );
}

/**
 * File data.
 *
 * @var         Array           $data           Lines of code.
 */
$data = Array ();

/** Read file data */
$data = file ( $argv[ 1 ] );

/**
 * Copy type annotation from @@param tag to function header.
 *
 * PHP disallow type hinting of scalar values in type header. On the other
 * hand doxygen mandate this behavior to generate correct documentation.
 */
$data = fix_function_header ( $data );

/**
 * Copy type annotation from @@var to member declaration.
 *
 * PHP disallow type declaration.
 */
$data = fix_var_declaration ( $data );

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
    '/^(\s*\*\s*)(\\\|@)license\s*(\S+)\s*(.*)/',
    "\\1\\2par License:\n\\1  \\3 \\4",
    $data
);

/**
 * Substitute @@package tag for paragraph titled Package and tag content.
 *
 * A @@package tag is usable only in java.
 */
$data = preg_replace (
    '/^(\s*\*\s*)(\\\|@)package\s*(\S+)/',
    "\\1\\2par Package:\n\\1  %\\3",
    $data
);

/**
 * Substitute @@category tag for paragraph titled Category and the tag content.
 *
 * A @@category tag does exist in doxygen but it is used for Objective-C only.
 */
$data = preg_replace (
    '/^(\s*\*\s*)(\\\|@)category\s*(\S+)/',
    "\\1\\2par Category:\n\\1  %\\3",
    $data
);

/**
 * Enclose @@link tag with @@endlink
 *
 * A @@link tag does exists in doxygen but is must be enclosed by @@endlink
 * tag.
 */
$data = preg_replace (
    '/^(\s*\*\s*)((\\\|@)link\s+\S+)/',
    "\\1\\3par URL:\n\\1  \\2 \\3endlink",
    $data
);

/**
 * Strip type annotation from @@param tag
 *
 * A type annotation in @@param tag (mandatory in php) is prohibited in
 * doxygen.
 */
$data = preg_replace (
    '/^(\s*\*\s*)(\\\|@)param\s+\S+\s+(.*)$/',
    "\\1\\2param \\3",
    $data
);

/**
 * Strip type annotation from @@return tag
 *
 * A type annotation in @@return tag (mandatory in php) is prohibited in
 * doxygen.
 */
$data = preg_replace (
    '/^(\s*\*\s*)(\\\|@)return\s+\S+/',
    "\\1\\2return",
    $data
);

echo implode ( $data );
