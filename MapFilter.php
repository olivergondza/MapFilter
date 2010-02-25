<?php
/**
* Class to filter associative arrays
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

require_once ( __DIR__ . MapFilter::PACKAGE_DIR . "/Pattern.php" );
require_once ( __DIR__ . MapFilter::PACKAGE_DIR . "/SerializedPattern.php" );
require_once ( __DIR__ . MapFilter::PACKAGE_DIR . "/MapFilter_Exception.php" );

class MapFilter {
  
  const PACKAGE_DIR = "/MapFilter/";
  
  /** Key that will be used as default value */
  const DEFAULT_VALUE = 0;
  
  /** Strict array search */
  const STRICT_SEARCH = TRUE;
  
  protected $parseCall = Array ();
  
  /**
  * Query MapFilter_Pattern.
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

  /**
  * Load Pattern from the file
  * @filename: String
  * @return: MapFilter_Pattern
  */
  public function loadPattern ( $filename ) {
  
    $sPattern = MapFilter_SerializedPattern::fromFile ( $filename );
    
    return $this->setPattern (
        $sPattern->fetch ()
    );
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
    if ( $this->pattern instanceof MapFilter_Pattern ) {

      $tempPattern = clone $this->pattern;
    }

    /** Resolve all dependencies */
    $this->satisfy ( $tempPattern );

    /** Prevent old result leaking to the new result set*/
    $this->data = Array ();
    $this->pickUp ( $tempPattern );

    return $this->fetch ();
  }
  
  /**
  * Satisfy all dependencies
  * @pattern: MapFilter_Pattern; Current tree node
  *
  */
  private function satisfy ( MapFilter_Pattern $pattern = NULL ) {

    /** Table to map nodetypes an their satisfy callbacks */
    $calls = Array (
        MapFilter_Pattern::NODETYPE_ATTR => Array ( $this, "satisfyValueNode" ),
        MapFilter_Pattern::NODETYPE_OPT => Array ( $this, "satisfyOptNode" ),
        MapFilter_Pattern::NODETYPE_SOME => Array ( $this, "satisfySomeNode" ),
        MapFilter_Pattern::NODETYPE_ALL => Array ( $this, "satisfyAllNode" ),
        MapFilter_Pattern::NODETYPE_ONE => Array ( $this, "satisfyOneNode" ),
        MapFilter_Pattern::NODETYPE_KEYATTR => Array ( $this, "satisfyKeyAttrNode" ),
    );

    /** Call appropriate satisfy method */
    return call_user_func (
        $calls[ $pattern->nodeType ],
        $pattern
    );
  }
  
  /**
  * Satisfy node just if there are no unsatisfied follower.
  * Finding unsatisfied follower may stop mapping since there is no way to
  * satisfy parent of any further potentially satisfied follower.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfyAllNode ( MapFilter_Pattern $pattern = NULL ) {
  
    foreach ( $pattern->content as $follower ) {
      
      if ( ! $this->satisfy ( $follower ) ) {

        return $pattern->satisfied = FALSE;
      }
    }
    
    return $pattern->satisfied = TRUE;
  }
  
  /**
  * Satisfy node if there is one satisfied follower (any further followers
  * mustn't be satisfied in order to pick up just first one of those).
  * Mapping CAN'T continue after finding satisfied follower.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfyOneNode ( MapFilter_Pattern $pattern = NULL ) {
  
    foreach ( $pattern->content as $follower ) {
      
      if ( $this->satisfy ( $follower ) ) {

        return $pattern->satisfied = TRUE;
      }
    }
    
    return $pattern->satisfied = FALSE;
  }
  
  /**
  * That node is always satisfyied.
  * Thus satisfy MUST be mapped on ALL followers.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfySomeNode ( MapFilter_Pattern $pattern = NULL ) {
  
    $satisfiedFollowers = array_map (
        Array ( $this, "satisfy" ),
        $pattern->content
    );

    return $pattern->satisfied = in_array (
        TRUE,
        $satisfiedFollowers,
        TRUE /** Compare strictly */
    );
  }
  
  /**
  * Satisfy node when there is at least one satisfied follower.
  * Thus satisfy MUST be mapped on ALL followers.
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfyOptNode ( MapFilter_Pattern $pattern = NULL ) {
  
    array_map (
        Array ( $this, "satisfy" ),
        $pattern->content
    );

    return $pattern->satisfied = TRUE;
  }
  
  /**
  * Tests just existence of an attribute, nothing more
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfyValueNode ( MapFilter_Pattern $pattern = NULL ) {
  
    return $pattern->satisfied = $this->attrPresent (
        (String) $pattern
    );
  }
  
  /**
  * Satisfy node when there is at least one satisfied follower fitting value.
  * Just such follower should be part of the result.
  * WORKING FOR SOME REASON
  * @pattern: MapFilter_Pattern
  * @return: Bool
  */
  private function satisfyKeyAttrNode ( MapFilter_Pattern $pattern = NULL ) {

    $attrName = (String) $pattern;
    
    $attrExists = $this->attrPresent ( $attrName );
    
    if ( ! $attrExists ) {
      return FALSE;
    }
    
    /** Find a follower that fits an value filter and let it satisfy */
    foreach ( $pattern->content as $follower ) {
      
      $fits = $this->valueFits (
          $follower->valueFilter,
          $attrName
      );
      
      if ( $fits ) {
      
        $pattern->satisfied = $this->satisfy ( $follower );
        
        if ( $pattern->satisfied ) {
          return TRUE;
        }
      }
    }

    return FALSE;
  }
  
  /**
  * Enclose string with begining and end mark to ensure that the string are
  * completely equal.
  */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /** PREG string delimiter */
  const FILTER_DELIMITER = '/';
  
  /**
  * Test whether a onValue condition on tree node fits given pattern
  * @valueFilter: PREG; Validation pattern
  * @valueCandidate: String
  * @return: Bool
  */
  private function valueFits ( $valueFilter, $attrName ) {

    /** Sanitize inputed PREG */
    $valueCandidate = preg_quote (
        $this->query[ $attrName ],
        self::FILTER_DELIMITER
    );
  
    $valueFilter = sprintf (
        self::FILTER_BOUNDARIES,
        $valueFilter
    );

    $matchCount = preg_match (
        $valueFilter,
        $valueCandidate
    );

    /** Assumed match count is 1 (Equals) or 0 (Differs) */
    assert ( $matchCount < 2 );
    
    return (Bool) $matchCount;
  }
  
  /**
  * Test whether an argument is present in query.
  * Possible connection between simple array assert filter. Node would be 
  * satisfied when attribute value satisfies assert callback.
  * @attrName: String; Name of an attribute
  * @returns: Bool
  */
  private function attrPresent ( $attrName ) {
    
    return array_key_exists (
        $attrName,
        $this->query
    );
  }
  
  /**
  * Pick up valid data
  * @pattern: Satisfied pattern
  */
  private function pickUp ( MapFilter_Pattern $pattern ) {
  
    /** Pick a cherry */
    if ( MapFilter_Pattern::hasAttribute ( $pattern->nodeType ) ) {
      
      if ( $pattern->satisfied ) {

        $attrName = $pattern->attribute;

        if ( $this->attrPresent ( $attrName ) ) {
          $this->data[ $attrName ] = $this->query[ $attrName ];
        }
      }
    }
    
    /** Crawl through node's followers */
    if ( MapFilter_Pattern::validNodeType ( $pattern->nodeType ) ) {

      if ( $pattern->satisfied ) {
      
        foreach ( $pattern->content as $follower ) {

          $this->pickUp ( $follower );
        }
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

      $this->pattern = clone ( $pattern );
    
    /** Deserialize pattern file */
    } elseif ( is_readable ( $pattern ) ) {

      $this->pattern = MapFilter_SerializedPattern::fromFile ( $pattern );

    /** Create pattern from XML string */
    } elseif ( is_string ( $pattern ) ) {

      $this->pattern = MapFilter_SerializedPattern::load ( $pattern );

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
