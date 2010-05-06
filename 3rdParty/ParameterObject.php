<?php
/**
* Abstract Parameter Object.
*
* @author	Oliver Gondža
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	3rdParty
*/

/**
* @file		ParameterObject/Exception.php
*/
require_once ( dirname ( __FILE__ ) . '/ParameterObject/Exception.php' );

/**
* Universal Parameter Object.
*
* @class	ParameterObject
* @package	3rdParty
* @author	Oliver Gondža
*/
class ParameterObject {

  /**
  * Deal with property declaration and set its default value.
  * Declared property has integer index assigned and it's value should be
  * it's name thous string is expected.
  *
  * @param	property	A property to declare sent by reference
  * @param	value		Value of the property sent by reference
  *
  * @throws	ParameterObject_Exception::INVALID_DECLARATION
  */
  private function dispatchDeclaration ( &$property, &$value ) {
  
    if ( ! is_int ( $property ) ) {
      return;
    }
      
    if ( ! is_string ( $value ) ) {

      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_DECLARATION,
          Array ( gettype ( $value ) )
      );
    }
      
    $property = (String) $value;
    $value = NULL;
  }

  /**
  * Create an (inherited) object.
  *
  * @param	writable	Initialization data array
  */
  public function __construct ( Array $writable = Array () ) {
    
    foreach ( $writable as $property => $value ) {
    
      self::dispatchDeclaration ( $property, $value );
      
      $this->$property = $value;
    }

    return;
  }

  /**
  * Determine whether an object has a given property.
  *
  * @param	name	Property name
  *
  * @return	Bool
  */
  public function hasProperty ( $name ) {
  
    return property_exists ( $this, $name );
  }

  /**
  * Get property name from action name substring.
  *
  * @param	name	Property name candidate
  *
  * @return	String	New property name
  */
  private static function getPropertyName ( $name ) {
  
    return lcfirst ( $name );
  }
  
  /**
  * Set property value. Provide fluent interface.
  *
  * @param	name		Callback name
  * @param	arguments	Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return	Mixed
  */
  private function callSetter ( $name, Array $arguments ) {
  
    $property = self::getPropertyName ( $name );
  
    if ( count ( $arguments ) !== 1 ) {
     
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 1, count ( $arguments ) )
      );
    }
    
    if ( !$this->hasProperty ( $property ) ) {

      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_SET,
          Array ( get_class (), $property )
      );
    }
    
    $this->$property = $arguments[ 0 ];
    
    return $this;
  }
  
  /**
  * Get property value.
  *
  * @param	name	Callback name
  * @param	arguments	Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return	Mixed
  */
  private function callGetter ( $name, Array $arguments ) {
  
    $property = self::getPropertyName ( $name );
  
    if ( count ( $arguments ) !== 0 ) {
     
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 0, count ( $arguments ) )
      );
    }
    
    if ( !$this->hasProperty ( $property ) ) {

      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_GET,
          Array ( get_class (), $property )
      );
    }
    
    return $this->$property;
  }
  
  /**
  * Action pattern.
  *
  * A REGEXP to match for action to be valid.
  */
  const ACTION_PATTERN = '/^(set|get)([A-Z].*)$/';
  
  /**
  * Action-prefix to action-callback mapping.
  *
  * A mapping table to translate action prefixes to corresponding callbacks.
  *
  * @var	Array	$prefix2action
  */
  private $prefix2action = Array (
      'set' => 'callSetter',
      'get' => 'callGetter'
  );
  
  /**
  * Determine whether an action exists.
  *
  * @param	matches
  *
  * @return	Bool
  */
  private function actionExists ( $matches ) {

    assert ( count ( $matches ) === 3 );
    
    return (Bool) array_key_exists (
        $matches[ 1 ],
        $this->prefix2action
    );
  }
  
  /**
  * Call special method or throw an exception.
  *
  * @param	name	Callback name
  * @param	arguments Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_CALL
  */
  public function __call ( $name, Array $arguments ) {
  
    $match = (Bool) preg_match ( self::ACTION_PATTERN, $name, $matches );

    if ( !$match || !$this->actionExists ( $matches ) ) {
    
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_CALL,
          Array ( get_class (), $name )
      );
    }

    /** Call action with specified property */
    $action = $this->prefix2action[ $matches[ 1 ] ];
    $propertyName = $matches[ 2 ];
    
    return call_user_func (
        Array ( $this, $action ),
        $propertyName,
        $arguments
    );
  }
}
