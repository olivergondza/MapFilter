<?php
/**
* Class to filter associative arrays
*
* @since	0.1
* 
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	Filter
*/

/**
* MapFilter Interface
*
* @file		MapFilter/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Interface.php' );

/**
* MapFilter Null Pattern
*
* @file		MapFilter/Pattern/Null.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern/Null.php' );

/**
* Class to filter associative arrays.
*
* @since	0.1
*
* @class	MapFilter
* @author	Oliver Gondža
* @package	MapFilter
* @subpackage	Filter
*/
class MapFilter implements MapFilter_Interface {

  /**
  * Query Tree of Pattern.
  *
  * @since	0.4
  *
  * @var	MapFilter_Pattern_Interface	$pattern
  * @see	setPattern(), __construct()
  */
  private $pattern = NULL;

  /**
  * Read data / Query candidate
  *
  * @since	0.4
  *
  * @var	Array	$query
  * @see	setQuery(), __construct()
  */
  private $query = Array ();
  
  /**
  * Determine whether the filter configuration has been parsed
  *
  * @since	0.4
  *
  * @var	Bool	$parsed
  * @see	parse(), setQuery(), setPattern()
  */
  private $parsed = FALSE;
  
  /**
  * @copyfull{MapFilter_Interface::__construct()}
  */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL,
      Array $query = Array ()
  ) {
    
    $this->setPattern ( $pattern );
    
    $this->setQuery ( $query );

    return;
  }

  /**
  * @copyfull{MapFilter_Interface::setPattern()}
  */
  public function setPattern ( MapFilter_Pattern_Interface $pattern = NULL) {

    $this->parsed = FALSE;

    if ( $pattern === NULL) {
    
      $this->pattern = new MapFilter_Pattern_Null ();
    } else {

      $this->pattern = clone ( $pattern );
    }
    
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_Interface::setQuery()}
  */
  public function setQuery ( Array $query ) {
  
    $this->parsed = FALSE;
  
    $this->query = $query;
    return $this;
  }
  
  /**
  * Parse filter configuration.
  *
  * Direct call of this method is no longer necessery since it is being called
  * automatically during result obtaining.
  *
  * @since	0.1
  *
  * @see	__construct(), getResults(), getAsserts(), getFlags()
  */
  public function parse () {
  
    if ( $this->parsed ) {
      
      return;
    }
  
    $this->parsed = TRUE;
  
    $this->pattern->parse ( $this->query );
    
    return;
  }
  
  /**
  * @copyfull{MapFilter_Interface::getResults()}
  */
  public function getResults () {

    $this->parse ();

    return $this->pattern->getResults ();
  }
  
  /**
  * @copyfull{MapFilter_Interface::getAsserts()}
  */
  public function getAsserts () {
  
    $this->parse ();
  
    return $this->pattern->getAsserts ();
  }
  
  /**
  * @copyfull{MapFilter_Interface::getFlags()}
  */
  public function getFlags () {
  
    $this->parse ();
  
    return $this->pattern->getFlags();
  }
  
  /**
  * Alias for getResults(). Just here for maintain backward compatibility
  *
  * @since	0.1
  *
  * @deprecated	since 0.2
  * @see	getResults()
  */
  public function fetch () {
  
    $level = ( defined ( 'E_USER_DEPRECATED' ) )
        ? E_USER_DEPRECATED
        : E_USER_NOTICE
    ;

    $message = sprintf (
        '%s::%s () is deprecated. Use %s::getResults () instead.',
        __CLASS__, __FUNCTION__, __CLASS__
    );
  
    trigger_error ( $message, $level );
    
    $this->parse ();
  
    return $this->getResults ();
  }
}
