<?php
/**
 * Class to handle exceptions less ugly way.
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
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * Enhanced exception handling class.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_Exception
 * @author   Oliver Gondža <324706@mail.muni.cz>
 *
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
class MapFilter_Exception extends Exception {

  /**
   * Message format.
   *
   * A callback to use for exception message formatting.
   */
  const MESSAGE_FORMAT = 'sprintf';

  /**
   * Exception message.
   *
   * @var	String		$message
   */
  protected $message = "";
  
  /**
   * Exception code.
   *
   * @var	Int		$code
   */
  protected $code = 0;
  
  /**
   * Exception args.
   *
   * @var	Array		$args
   */
  protected $args = Array ();

  /**
   * Message format strings.
   *
   * @var	Array		$messages
   */
  protected $messages = Array ();

  /**
   * Create exception from @code and @args from predefined exceptions
   * and their messages.
   *
   * @param     Int             $code           Exception code
   * @param     Array           $args           Arguments
   */
  public function __construct ( $code, Array $args = Array () ) {

    assert ( is_int ( $code ) );

    $this->code = $code;
    $this->args = $args;
    $this->message = $this->formatMessage ( $code, $args );
    parent::__construct ( $this->message, $this->code );
  }

  /**
   * Get formatted message.
   *
   * @param     Int             $code           Exception code
   * @param     Array           $args           Arguments
   *
   * @return    String          Exception message
   */
  protected function formatMessage ( $code, Array $args ) {

    assert ( is_int ( $code ) );

    array_unshift ( $args, $this->messages[ $code ] );

    $message = call_user_func_array (
        self::MESSAGE_FORMAT,
        $args
    );
    
    return $message;
  }
  
  /**
   * Get just message without trace when typecast to string
   *
   * @return    String          String representation of an object
   */
  public function __toString () {

    return (String) $this->message;
  }
}
/** @endcode */