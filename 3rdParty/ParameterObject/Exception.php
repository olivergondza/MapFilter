<?php
/**
* Parameter Object Exceptions.
*
* @author	Oliver Gondža
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	3rdParty
*/

/**
* ParameterObject exception.
*
* @class 	ParameterObject_Exception
* @package	3rdParty
* @author	Oliver Gondža
*/
class ParameterObject_Exception extends PureException {

  /**
  * A property value can not be set.
  */
  const INVALID_SET = 1;

  /**
  * A property value can not be obtained.
  */
  const INVALID_GET = 2;

  /**
  * Invalid magic method called.
  */
  const INVALID_CALL = 3;

  /**
  * Too many arguments passed to the function.
  */
  const INVALID_ARG_COUNT = 4;

  /**
  * A property can not be declared.
  */
  const INVALID_DECLARATION = 5;
  
  /**
  * Exception messages
  */
  protected $messages = Array (
      self::INVALID_SET => "Class %s has no value like '%s'.",
      self::INVALID_GET => "Class %s has no value like '%s'.",
      self::INVALID_CALL => "Class %s has no method like '%s'.",
      self::INVALID_ARG_COUNT => "%s::%s () requires %d arguments, %d given.",
      self::INVALID_DECLARATION => "Invalid property declaration. String expected but %s given.",
  );
}
