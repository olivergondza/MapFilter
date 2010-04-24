<?php
/**
* MapFilter_Pattern_Tree Interface
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

interface MapFilter_Pattern_Tree_Interface {
  
  public function setAttribute ( $attribute );
  
  public function setDefault ( $default );
  
  public function setContent ( Array $content );
  
  public function setValuePattern ( $pattern );

  public function setValueFilter ( $valueFilter );
  
  public function setFlag ( $flag );
  
  public function setAssert ( $assert );
  
  public function __construct ();
  
  /**
  * Satisfy certain node type and let it's followers to get satisfied
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param );
  
  /**
  * Pick-up satisfaction results
  * @param MapFilter_Pattern_PickUpParam
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param );
  
  public function isSatisfied ();
  
  public static function attrPresent ( $attrName, $query );
}
