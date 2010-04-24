<?php
/**
* MapFilter Interface
* 
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

interface MapFilter_Interface {
  
  public function __construct (
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  );

  public function setPattern ( MapFilter_Pattern $pattern );
  
  public function setQuery ( Array $query );
  
  public function parse ();
  
  public function getResults ();
  public function fetch ();
  
  public function getAsserts ();
  
  public function getFlags ();
}
