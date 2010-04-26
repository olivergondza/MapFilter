<?php
/**
* Abstract Parameter Object
*
* @author Oliver Gondža
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
*/

/**
* Include exception class
*/
require_once ( dirname ( __FILE__ ) . '/ParameterObject/Exception.php' );

/**
* ParameterObject
*/
class MapFilter_ParameterObject {

  /**
  * Deal with property declaration and set its default value.
  * Declared property has integer index assigned and it's value should be
  * it's name thous string is expected.
  * @param &Mixed
  * @param &Mixed
  * @throws MapFilter_ParameterObject_Exception::INVALID_DECLARATION
  */
  private function dispatchDeclaration ( &$property, &$value ) {
  
    if ( ! is_int ( $property ) ) {
      return;
    }
      
    if ( ! is_string ( $value ) ) {

      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_DECLARATION,
          Array ( gettype ( $value ) )
      );
    }
      
    $property = (String) $value;
    $value = NULL;
  }

  /**
  * Create an (inherited) object.
  * @param Array ( String )
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
  * @param String
  * @return Bool
  */
  public function hasProperty ( $name ) {
  
    return property_exists ( $this, $name );
  }

  /**
  * Get property name from action name substring.
  * @param String
  * @return String
  */
  private static function getPropertyName ( $name ) {
  
    return lcfirst ( $name );
  }
  
  /**
  * Set property value. Provide fluent interface
  * @param String
  * @param Array ( String => Mixed )
  * @throws MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return Mixed
  */
  private function callSetter ( $name, Array $arguments ) {
  
    $property = self::getPropertyName ( $name );
  
    if ( count ( $arguments ) !== 1 ) {
     
      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 1, count ( $arguments ) )
      );
    }
    
    if ( !$this->hasProperty ( $property ) ) {

      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_SET,
          Array ( get_class (), $property )
      );
    }
    
    $this->$property = $arguments[ 0 ];
    
    return $this;
  }
  
  /**
  * Get property value
  * @param String
  * @param Array ( String => Mixed )
  * @throws MapFilter_Pattern_ParameterObject_Exception::INVALID_ARG_COUNT
  * @return Mixed
  */
  private function callGetter ( $name, Array $arguments ) {
  
    $property = self::getPropertyName ( $name );
  
    if ( count ( $arguments ) !== 0 ) {
     
      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_ARG_COUNT,
          Array ( get_class (), $name, 0, count ( $arguments ) )
      );
    }
    
    if ( !$this->hasProperty ( $property ) ) {

      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_GET,
          Array ( get_class (), $property )
      );
    }
    
    return $this->$property;
  }
  
  /**
  * Action REGEXP.
  * @var String
  */
  const ACTION_PATTERN = '/^(set|get)([A-Z].*)$/';
  
  /**
  * Action prefix to action callback mapping
  * @var Array ( String => String )
  */
  private $prefix2action = Array (
      'set' => 'callSetter',
      'get' => 'callGetter'
  );
  
  /**
  * Determine whether an action exists.
  * @param Array
  * @return Bool
  */
  private function actionExists ( $matches ) {

    assert ( count ( $matches ) === 3 );
    
    return (Bool) array_key_exists (
        $matches[ 1 ],
        $this->prefix2action
    );
  }
  
  /**
  * Call special method or throw an exception
  * @param String
  * @param Array ( String => Mixed )
  * @throws MapFilter_Pattern_ParameterObject_Exception::INVALID_CALL
  */
  public function __call ( $name, Array $arguments ) {
  
    $match = (Bool) preg_match ( self::ACTION_PATTERN, $name, $matches );

    if ( !$match || !$this->actionExists ( $matches ) ) {
    
      throw new MapFilter_ParameterObject_Exception (
          MapFilter_ParameterObject_Exception::INVALID_CALL,
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
