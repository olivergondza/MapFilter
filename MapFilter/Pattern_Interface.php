<?php
/**
* MapFilter_Pattern Interface
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

interface MapFilter_Pattern_Interface {

  public function __construct ( MapFilter_Pattern_Tree $patternTree );

  public function getTree ();

  public static function load ( $XMLSource );
  
  public static function fromFile ( $url );
  
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param );
  
  public function pickUp ( MapFilter_Pattern_PickUpParam $param );

  public function __clone ();
}