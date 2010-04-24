<?php
/**
* Class to filter associative arrays
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern.php' );
require_once ( dirname ( __FILE__ ) . '/MapFilter_Interface.php' );

class MapFilter implements MapFilter_Interface {
  
  /**
  * Query Tree of Pattern.
  * @var MapFilter_Pattern
  */
  private $pattern = NULL;

  /**
  * Read data / Query candidate
  * @var Array ( attrCandidate => valueCandidate )
  */
  private $query = Array ();
  
  /**
  * Validated data
  * @var Array ( attr => value )
  */
  private $results = Array ();
  
  /**
  * Validation asserts
  * @var Array ( String )
  */
  private $asserts = Array ();
  
  /**
  * Validation flags
  * @var Array ( String )
  */
  private $flags = Array ();
  
  /**
  * Instantiate and do all the stuff
  * @param MapFilter_Pattern
  * @param Array; Input query to parse
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
  * @param MapFilter_Pattern
  */
  public function setPattern ( MapFilter_Pattern $pattern ) {

    $this->pattern = clone ( $pattern );
    return $this;
  }
  
  /**
  * Fluent Method that sets query to filter
  * @param Array
  */
  public function setQuery ( Array $query ) {
  
    $this->query = $query;
    return $this;
  }
  
  /**
  * Resolve tree dependencies, filter, pick up the results and return filtered
  * query.
  * @return Array
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
  * Export parsed structure
  * @return Array ( Attr => Value )
  */
  public function getResults () {

    return $this->results;
  }
  
  /**
  * alias for getResults (). Just here for maintain backward compatibility
  * @return Array ( Attr => Value )
  */
  public function fetch () {
  
    return $this->getResults ();
  }
  
  /**
  * Get validation asserts.
  * @return Array ( String )
  */
  public function getAsserts () {
  
    return $this->asserts;
  }
  
  /**
  * Get sat flags.
  * @return Array ( String )
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
