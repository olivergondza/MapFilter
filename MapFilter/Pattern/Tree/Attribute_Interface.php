<?php
/**
* Every tree node that has an attribute must implement this interface
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
interface MapFilter_Pattern_Tree_Attribute_Interface {

  public function __toString ();
  
  public function setAttribute ( $attribute );
}