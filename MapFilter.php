<?php
/**
* Class to filter associative arrays
* 
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include pattern class
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern.php' );

/**
* Include class interface
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter_Interface.php' );

/**
* Class to filter associative arrays
* @package MapFilter
*/
class MapFilter implements MapFilter_Interface {
  
  /**
  * Query Tree of Pattern.
  * @var MapFilter_Pattern
  */
  private $pattern = NULL;

  /**
  * Read data / Query candidate
  * @var Array
  */
  private $query = Array ();
  
  /**
  * Validated data
  * @var Array
  */
  private $results = Array ();
  
  /**
  * Validation asserts
  * @var Array
  */
  private $asserts = Array ();
  
  /**
  * Validation flags
  * @var Array
  */
  private $flags = Array ();
  
  /**
  * Create new filter instance. If are pattern and query sat trigger parsing
  * procedure.
  * @see setPattern, setQuery, parse, MapFilter_Pattern
  * @param MapFilter_Pattern
  * @param Array $query A query to filter.
  */
  public function __construct (
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  ) {
    
    if ( !$pattern ) {
      return;
    }

    $this->setPattern ( $pattern );
    $this->setQuery ( $query );

    $this->parse ();
    
    return;
  }

  /**
  * Fluent Method that sets desired query pattern
  * @see __construct
  * @param MapFilter_Pattern
  * @return MapFilter Instance of MapFilter with new pattern
  */
  public function setPattern ( MapFilter_Pattern $pattern ) {

    $this->pattern = clone ( $pattern );
    return $this;
  }
  
  /**
  * Fluent Method that sets query to filter
  * @see __construct
  * @param Array
  * @return MapFilter Instance of MapFilter with new query
  */
  public function setQuery ( Array $query ) {
  
    $this->query = $query;
    return $this;
  }
  
  /**
  * Resolve tree dependencies, filter, pick up the results and return filtered
  * query.
  * @see __construct, getResults
  * @return Array Parsing results
  */
  public function parse () {
  
    $this->cleanup ();
  
    /** Return untouched query in case there is no pattern */
    if ( !$this->pattern || !$this->pattern->getTree () ) {

      $this->results = $this->query;
      return $this->getResults ();
    }
    
    /**
    *  Create temporary copy of pattern since it will be modified during
    *  the parsing procedure
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

    return $this->getResults ();
  }
  
  /**
  * Get parsed query from latest parsing process.
  * @see parse
  * @return Array () Parsing results
  */
  public function getResults () {

    return $this->results;
  }
  
  /**
  * Alias for getResults (). Just here for maintain backward compatibility
  * @deprecated since 0.2
  * @see getResults
  * @return Array ()
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
  
    return $this->getResults ();
  }
  
  /**
  * Get validation assertions from latest parsing process.
  * @see parse
  * @return Array () Parsing asserts
  */
  public function getAsserts () {
  
    return $this->asserts;
  }
  
  /**
  * Get flags sat during latest parsing process.
  * @see parse
  * @return Array () Parsing flags
  */
  public function getFlags () {
  
    return $this->flags;
  }

  /**
  * Clean up object storage that enables to parse multiple queries with
  * the same pattern with no need to re-instantiate object
  */
  private function cleanup () {
  
    $this->results = Array ();
    $this->asserts = Array ();
    $this->flags = Array ();
  
    return;
  }
}
