<?php
/**
* Class to handle exceptions less ugly way.
*
* @author	Oliver Gondža
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	3rdParty
*/

/**
* Enhanced exception handling class.
*
* @class	PureException
* @package	3rdParty
* @author	Oliver Gondža
*/
class PureException extends Exception {

  /**
  * Message format.
  *
  * A callback to use for exception message formatting.
  */
  const MESSAGE_FORMAT = 'sprintf';

  /**
  * Exception message.
  *
  * @var	String	$message
  */
  protected $message = "";
  
  /**
  * Exception code.
  *
  * @var	Int	$code
  */
  protected $code = 0;
  
  /**
  * Exception args.
  *
  * @var	Array	$args
  */
  protected $args = Array ();

  /**
  * Message format strings.
  *
  * @var	Array	$messages
  */
  protected $messages = Array ();

  /**
  * Create exception from @code and @args from predefined exceptions
  * and their messages.
  *
  * @param	code	Exception code
  * @param	args	Arguments
  */
  public function __construct ( $code, $args = Array () ) {

    $this->code = $code;
    $this->args = $args;
    $this->message = $this->formatMessage ( $code, $args );
    parent::__construct ( $this->message );

    return;
  }

  /**
  * Get formatted message.
  *
  * @param	code	Exception code
  * @param	args	Arguments
  * @return	String	Exception message
  */
  protected function formatMessage ( $code, $args ) {

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
  * @return	String	String representation of an object
  */
  public function __toString () {

    return $this->message;
  }
}
/** @endcode */