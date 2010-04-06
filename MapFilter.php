<?php
/**
* Class to filter associative arrays
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . "/MapFilter/Pattern.php" );
require_once ( dirname ( __FILE__ ) . "/MapFilter/Exception.php" );

class MapFilter {
  
  /**
  * Query Tree of Pattern.
  * @var: MapFilter_Pattern
  */
  public $pattern = NULL;

  /**
  * Read data / Query candidate
  * @var: Array ( attrCandidate => valueCandidate )
  */
  private $query = Array ();
  
  /**
  * Validated data
  * @var: Array ( attr => value )
  */
  private $data = Array ();
  
  /**
  * Validation asserts
  * @var: Array ( String )
  */
  private $asserts = Array ();
  
  /**
  * Validation flags
  * @var: Array ( String )
  */
  private $flags = Array ();
  
  /**
  * Instantiate and do all the stuff
  * @pattern: MapFilter_Pattern
  * @query: Array; Input query to parse
  */
  public function __construct (
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  ) {
    
    $this->setPattern ( $pattern );
    $this->setQuery ( $query );

    $this->parse ();
    
    return;
  }

  /**
  * Set desired query pattern from Existing MapFilter_Pattern object,
  * @pattern: MapFilter_Pattern
  */
  public function setPattern ( MapFilter_pattern $pattern ) {

    $this->pattern = clone ( $pattern );

    return;
  }
  
  /**
  * Set query to filter
  * @query: Array
  */
  public function setQuery ( Array $query ) {
  
    $this->query = $query;
    return;
  }
  
  /**
  * Export parsed structure
  * @return: Array ( Attr => Value )
  */
  public function fetch () {

    return $this->data;
  }
  
  /**
  * Get validation asserts.
  * @return: Array ( String )
  */
  public function getAsserts () {
  
    return $this->asserts;
  }
  
  /**
  * Get sat flags.
  * @return: Array ( String )
  */
  public function getFlags () {
  
    return $this->flags;
  }

  /**
  * Resolve tree dependencies, filter and pick up the results.
  * @return: Array
  */
  public function parse () {
  
    $this->cleanup ();
  
    /** Return untouched query in case there is no pattern */
    if ( !$this->pattern || !$this->pattern->getTree () ) {

      $this->data = $this->query;
      return $this->fetch ();
    }
    
    /**
    *  Create temporary copy of pattern since it will be modified during
    *  the parsing procedure
    */
    $tempPattern = clone ( $this->pattern );

    /** Resolve all dependencies */
    $tempPattern->satisfy ( $this->query, $this->asserts );

    /** Prevent old result leaking to the new result set*/
    $this->pickUp ( $tempPattern->getTree () );

    return $this->fetch ();
  }
  
  /**
  * Pick up valid data
  * @pattern: MapFilter_Pattern_Node_Abstract
  */
  private function pickUp ( MapFilter_Pattern_Node_Abstract $pattern ) {
  
    /** Set assert for nodes that hasn't been satisfied and stop recursion */
    if ( !$pattern->isSatisfied () ) {

      return;
    }
  
    /** Set flag from satisfied node */
    if ( $pattern->flag !== NULL ) {
    
      $this->flags[] = $pattern->flag;
    }
  
    /** Pick an attribute from the leaves of tree */
    if ( $pattern->hasAttr () ) {
      
      $attrName = $pattern->attribute;

      if ( $pattern->attrPresent ( $attrName, $this->query ) ) {

        $this->data[ $attrName ] = $this->query[ $attrName ];
      } else {

        /** Attr satisfy error */
        assert ( FALSE );
      }
    }
    
    /** Crawl through node's followers */
    if ( $pattern->hasFollowers () ) {
      
      foreach ( $pattern->getContent () as $follower ) {

        $this->pickUp ( $follower );
      }
    }
    
    return;
  }
  
  /**
  * Clean up object storage that enables to parse multiple queries with
  * the same pattern with no need to re-instantiate object
  */
  private function cleanup () {
  
    $this->data = Array ();
    $this->asserts = Array ();
    $this->flags = Array ();
  
    return;
  }
  
  /**
  * Invoke class statically
  * @pattern: MapFilter_Pattern
  * @query: Array
  */
  public function __invoke (
      MapFilter_Pattern $pattern,
      Array $query
  ) {
    
    $filter = new MapFilter ( $pattern, $query );
    return $filter->fetch ();
  }
}
