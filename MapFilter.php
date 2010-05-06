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
* Class to filter associative arrays.
*
* @since	0.1
*
* @class	MapFilter
* @author	Oliver Gondža
* @package	MapFilter
*/
class MapFilter {

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
  * Create new filter instance.
  *
  * @since	0.1
  *
  * @param	pattern		A pattern to set
  * @param	query		A query to filter
  *
  * If no pattern specified an untouched query will be returned:
  *
  * @clip{User.test.php,testEmptyPattern}
  *
  * All parsing is done just in time (however it can be triggered manually using
  * MapFilter::parse()) when some of parsing results is accessed (in this case
  * when MapFilter::getResults() is called for the first time):
  *
  * @clip{User.test.php,testDuration}
  *
  * @see	setPattern(), setQuery(), MapFilter_Pattern()
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
  * Set desired query pattern.
  *
  * Fluent Method
  *
  * @since	0.1
  *
  * @param	pattern		A pattern to set
  *
  * @return	MapFilter	Instance of MapFilter with new pattern
  *
  * MapFilter can be configured using both constructor and specialized fluent
  * methods MapFilter::setPattern() and MapFilter::setQuery():
  *
  * @clip{MapFilter.test.php,testInvocation}
  *
  * @see	__construct()
  */
  public function setPattern ( MapFilter_Pattern $pattern ) {

    $this->parsed = FALSE;

    $this->pattern = clone ( $pattern );
    return $this;
  }
  
  /**
  * Set query to filter.
  *
  * @since	0.1
  *
  * @param	query	A query to set
  *
  * @return	MapFilter	Instance of MapFilter with new query
  *
  * MapFilter can be configured using both constructor and specialized fluent
  * methods MapFilter::setPattern() and MapFilter::setQuery():
  *
  * @clip{MapFilter.test.php,testInvocation}
  *
  * @see	__construct()
  * @todo	Arbitrary iterator query should be supported
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
  * Get results.
  *
  * Get parsed query from latest parsing process.
  *
  * @since	0.2
  *
  * @return	Array	Parsing results
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
  * Get validation assertions.
  *
  * Return validation asserts that was raised during latest parsing process.
  *
  * @since	0.1
  *
  * @return	Array	Parsing asserts
  */
  public function getAsserts () {
  
    $this->parse ();
  
    return $this->asserts;
  }
  
  /**
  * Get flags
  *
  * Return flags that was sat during latest parsing process.
  *
  * @since	0.1
  *
  * @return	Array	Parsing flags
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
