<?php
/**
* Abstract Parameter Object
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
class MapFilter_ParameterObject_Exception extends PureException {

  const INVALID_SET = 1;
  const INVALID_GET = 2;
  const INVALID_CALL = 3;
  const INVALID_ARG_COUNT = 4;
  const INTERNAL_INCRIMINATION = 5;
  const INVALID_DECLARATION = 6;
  
  public $messages = Array (
      self::INVALID_SET => "Class %s has no value like '%s'.",
      self::INVALID_GET => "Class %s has no value like '%s'.",
      self::INVALID_CALL => "Class %s has no method like '%s'.",
      self::INVALID_ARG_COUNT => "%s::%s () requires %d arguments, %d given.",
      self::INTERNAL_INCRIMINATION => "%s::%s can not be modified. Internal property.",
      self::INVALID_DECLARATION => "Invalid property declaration. String expected but %s given.",
  );
}