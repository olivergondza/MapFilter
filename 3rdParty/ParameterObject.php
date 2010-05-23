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
  * @param	String	property	A property to declare sent by reference
  * @param	Mixed	value		Value of the property sent by reference
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
  * Reference foreach is used in order to enable reference assigninment.
  *
  * @param	Array		writable	Initialization data array
  *
  * @return	ParameterObject			A new object
  */
  public function __construct ( Array $writable = Array () ) {
    
    foreach ( $writable as $property => &$value ) {
    
      self::dispatchDeclaration ( $property, $value );
      
      $this->$property = &$value;
    }
  }

  /**
  * Determine whether an object has a given property.
  *
  * @param	String		name		A property name
  *
  * @return	Bool		Has or has not a property
  */
  public function hasProperty ( $name ) {
  
    return property_exists ( $this, $name );
  }

  /**
  * Get property name from action name substring.
  *
  * @param	String	name	Property name candidate
  *
  * @return	String	New property name
  */
  private static function getPropertyName ( $name ) {
  
    return lcfirst ( $name );
  }
  
  /**
  * Set property value. Provide fluent interface.
  *
  * @param	String		name		Callback name
  * @param	Array		arguments	Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return	ParameterObject
  */
  private function callSetter ( $name, Array &$arguments ) {
  
    if ( count ( $arguments ) !== 1 ) {
     
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 1, count ( $arguments ) )
      );
    }
    
    $property = self::getPropertyName ( $name );
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
  * Set property reference. Provide fluent interface.
  *
  * @param	String		name		Callback name
  * @param	Array		arguments	Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return	ParameterObject
  */
  private function callRefSetter ( $name, Array &$arguments ) {
  
    if ( count ( $arguments ) !== 1 ) {
     
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 1, count ( $arguments ) )
      );
    }
    
    $property = self::getPropertyName ( $name );
    if ( !$this->hasProperty ( $property ) ) {

      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_REFSET,
          Array ( get_class (), $property )
      );
    }
    
    $this->$property = &$arguments[ 0 ];
    
    return $this;
  }
  
  /**
  * Get property value.
  *
  * @param	String	name		Callback name
  * @param	Array	arguments	Function arguments
  *
  * @throws	MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return	ParameterObject
  */
  private function &callGetter ( $name, Array &$arguments ) {
  
    if ( count ( $arguments ) !== 0 ) {
     
      throw new ParameterObject_Exception (
          ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 0, count ( $arguments ) )
      );
    }
    
    $property = self::getPropertyName ( $name );
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
  const ACTION_PATTERN = '/^(?<prefix>setRef|set|get)(?<name>[A-Z].*)$/';
  
  /**
  * Action-prefix to action-callback mapping.
  *
  * A mapping table to translate action prefixes to corresponding callbacks.
  *
  * @var	Array	$prefix2action
  */
  private $prefix2action = Array (
      'setRef' => 'callRefSetter',
      'set' => 'callSetter',
      'get' => 'callGetter',
  );
  
  /**
  * Determine whether an action exists.
  *
  * @param	Array	matches
  *
  * @return	Bool
  */
  private function actionExists ( $matches ) {

    return (Bool) array_key_exists (
        $matches[ 'prefix' ],
        $this->prefix2action
    );
  }
  
  /**
  * Call special method or throw an exception.
  *
  * @param	String		name		Callback name
  * @param	Array		arguments	Function arguments
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
    $callback = Array (
        $this,
        $this->prefix2action[ $matches[ 'prefix' ] ]
    );
    
    /** Call-time pass by reference override */
    return call_user_func (
        $callback,
        $matches[ 'name' ],
        $args =& $arguments
    );
  }
}
