<?php
/**
* Class to handle exceptions less ugly way
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

class PureException extends Exception {

  /** Message format callback */
  const MESSAGE_FORMAT = "sprintf";

  /**
  * Exception message
  * @var: String
  */
  protected $message = "";
  
  /**
  * Exception code
  * @var: Int
  */
  protected $code = 0;
  
  /**
  * Exception args
  * @var: Array ( Mixed )
  */
  protected $args = Array ();

  /**
  * Message format strings
  * @var: Array ( String )
  */
  protected $messages = Array ();

  /**
  * Create exception from @code and @args from predefined exceptions
  * and their messages
  * @code: Int; ! use constants !
  * @args: Array ( Mixed ); Array of args to format output string
  */
  public function __construct ( $code, $args = Array () ) {

    $this->code = $code;
    $this->args = $args;
    $this->message = $this->formatMessage ( $code, $args );
    parent::__construct ( $this->message );

    return;
  }

  /**
  * Get formated message
  * @code: Int; ! use constants !
  * @args: Array ( Mixed ); Array of args to format output string
  * @return: String; Formated message
  */
  protected function formatMessage ( $code, $args ) {

    array_unshift (
        $args,
        $this->messages[ $code ]
    );

    $message = call_user_func_array (
        self::MESSAGE_FORMAT,
        $args
    );
    
    return $message;
  }
  
  /**
  * Rethrow the same exception
  * @throws: my exception
  */
  public function rethrow () {
    
    $formerType = get_class ( $this );
    throw new $formerType (
        $this->code,
        $this->args
    );
  }
  
  /**
  * Get just message without trace when typecast to string
  * @return: String
  */
  public function __toString () {

    return $this->message;
  }
}