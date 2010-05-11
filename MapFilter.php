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
*
* @package	MapFilter
*/

/**
* MapFilter Pattern
*
* @file		MapFilter/Pattern.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern.php' );

/**
* MapFilter Interface
*
* @file		MapFilter/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Interface.php' );

/**
* Class to filter associative arrays.
*
* @since	0.1
*
* @class	MapFilter
* @author	Oliver Gondža
* @package	MapFilter
*/
class MapFilter implements MapFilter_Interface {

  /**
  * Query Tree of Pattern.
  *
  * @since	0.4
  *
  * @var	MapFilter_Pattern	$pattern
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
  * Validated data
  *
  * @since	0.4
  *
  * @var	Array	$results
  * @see	getResults(), parse()
  */
  private $results = Array ();
  
  /**
  * Validation asserts
  *
  * @since	0.4
  *
  * @var	Array	$asserts
  * @see	getAsserts(), parse()
  */
  private $asserts = Array ();
  
  /**
  * Validation flags
  *
  * @since	0.4
  *
  * @var	Array	$flags
  * @see	getFlags(), parse()
  */
  private $flags = Array ();
  
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
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  ) {
    
    if ( $pattern ) {

      $this->setPattern ( $pattern );
    }
    
    $this->setQuery ( $query );

    return;
  }

  /**
  * @copyfull{MapFilter_Interface::setPattern()}
  */
  public function setPattern ( MapFilter_Pattern $pattern ) {

    $this->parsed = FALSE;

    $this->pattern = clone ( $pattern );
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
  * @since	0.1
  *
  * @see	__construct(), getResults(), getAsserts(), getFlags()
  */
  public function parse () {
  
    if ( $this->parsed ) {
      
      return;
    }
  
    $this->cleanup ();
  
    /** Return untouched query in case there is no pattern */
    if ( !$this->pattern || !$this->pattern->getTree () ) {

      $this->results = $this->query;
      
      $this->parsed = TRUE;
      
      return;
    }
    
    /**
    * Create temporary copy of pattern since it will be modified during
    * the parsing procedure
    */
    $tempPattern = clone ( $this->pattern );

    /** Resolve all dependencies */
    $satisfyParam = new MapFilter_Pattern_SatisfyParam ();
    $satisfyParam->setQuery ( $this->query );
    $satisfyParam->asserts = &$this->asserts;
    $satisfyParam->flags = &$this->flags;

    $tempPattern->satisfy ( $satisfyParam );

    /** Pick up data */
    $pickUpParam = new MapFilter_Pattern_PickUpParam ();
    $pickUpParam->data = &$this->results;

    $tempPattern->pickUp ( $pickUpParam );

    $this->parsed = TRUE;

    return;
  }
  
  /**
  * @copyfull{MapFilter_Interface::getResults()}
  */
  public function getResults () {

    $this->parse ();

    return $this->results;
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
  
  /**
  * @copyfull{MapFilter_Interface::getAsserts()}
  */
  public function getAsserts () {
  
    $this->parse ();
  
    return $this->asserts;
  }
  
  /**
  * @copyfull{MapFilter_Interface::getFlags()}
  */
  public function getFlags () {
  
    $this->parse ();
  
    return $this->flags;
  }

  /**
  * Clean up object storage.
  *
  * @since	0.4
  *
  * This enables to parse multiple queries with the same pattern with no need 
  * to re-instantiate the object.
  */
  private function cleanup () {
  
    $this->results = Array ();
    $this->asserts = Array ();
    $this->flags = Array ();
  
    return;
  }
}
