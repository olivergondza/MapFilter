<?php
/**
* Class to filter associative arrays
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . MapFilter::PACKAGE_DIR . "/Pattern.php" );
require_once ( dirname ( __FILE__ ) . MapFilter::PACKAGE_DIR . "/Exception.php" );

class MapFilter {
  
  const PACKAGE_DIR = "/MapFilter/";
  
  /** Key that will be used as default value */
  const DEFAULT_VALUE = 0;
  
  /** Strict array search */
  const STRICT_SEARCH = TRUE;
  
  /**
  * Query MapFilter_Pattern_Node_Abstract.
  * @var: Tree of Pattern
  */
  public $pattern = NULL;
  
  /**
  * Read data / Query candidate
  * @var: Array of attrCandidate => valueCandidate
  */
  private $query = Array ();
  
  /**
  * Validated data
  * @var: Array of attr => value
  */
  private $data = Array ();
  
  /**
  * Do all the stuff
  * @query: Array; Input query to parse
  * @pattern: MapFilter_Pattern | String; pattern to parse or filename to one
  */
  public function __construct (
      $pattern = NULL,
      Array $query = Array ()
  ) {
    
    $this->setPattern ( $pattern );
    $this->setQuery ( $query );

    $this->parse ();
    
    return;
  }

  /** Resolve tree dependencies and pick up the results */
  public function parse () {
  
    /** No query is always satisfied, having no data to fetch */
    if ( !$this->query ) {

      $this->data = Array ();
      return $this->fetch ();
    }
    
    /** Return untouched query in case there is no pattern */
    if ( !$this->pattern ) {

      $this->data = $this->query;
      return $this->fetch ();
    }
    
    /**
    *  Create temporary copy of pattern since it will be modified during
    *  the parsing procedure
    */
    $tempPattern = clone ( $this->pattern );

    /** Resolve all dependencies */
    $tempPattern->satisfy ( $this->query );

    /** Prevent old result leaking to the new result set*/
    $this->data = Array ();
    $this->pickUp ( $tempPattern );
//var_dump ( $tempPattern );
//var_dump ( $this->query );
    return $this->fetch ();
  }
  
  /**
  * Pick up valid data
  * @pattern: Satisfied pattern
  */
  private function pickUp ( MapFilter_Pattern_Node_Abstract $pattern ) {
  
    if ( !$pattern->satisfied ) {

      return;
    }
  
    /** Pick a cherry */
    if ( $pattern->hasAttr () ) {
      
      $attrName = $pattern->attribute;

      if ( $pattern->attrPresent ( $attrName, $this->query ) ) {

        $this->data[ $attrName ] = $this->query[ $attrName ];
      }
    }
    
    /** Crawl through node's followers */
    if ( $pattern->hasFollowers () ) {
      
      foreach ( $pattern->content as $follower ) {

        $this->pickUp ( $follower );
      }
    }
    
    return;
  }
  
  /**
  * Set desired query pattern from Existing MapFilter_Pattern object,
  * String XML specification or XML file
  * @pattern: MapFilter_Pattern | XML String | Filename String
  */
  public function setPattern ( $pattern ) {

    if ( $pattern === NULL ) {
      return;
    }

    /** Set existing pattern */
    if ( $pattern instanceof MapFilter_Pattern ) {

      $this->pattern = clone ( $pattern->patternTree );
    
    /** Deserialize pattern file */
    } elseif ( is_readable ( $pattern ) ) {

      $this->pattern = MapFilter_Pattern::fromFile ( $pattern ) -> patternTree;

    /** Create pattern from XML string */
    } elseif ( is_string ( $pattern ) ) {

      $this->pattern = MapFilter_Pattern::load ( $pattern ) -> patternTree;

    } else {
      
      throw new MapFilter_Exception (
          MapFilter_Exception::UNKNOWN_PATTERN_SOURCE,
          Array (
              (String) gettype ( $pattern )
          )
      );
    }

    return;
  }
  
  /**
  * Set query
  * @query: Array
  */
  public function setQuery ( Array $query ) {
  
    $this->data = Array ();
    $this->query = $query;
    return;
  }
  
  /**
  * Export parsed structure
  * @return: Array
  */
  public function fetch () {

    return $this->data;
  }
  
  /**
  * Invoke class statically
  */
  public function __invoke (
      $pattern = NULL,
      Array $query = Array ()
  ) {
    
    $filter = new MapFilter ( $pattern, $query );
    return $filter->fetch ();
  }
}
