<?php
/**
* MapFilter_Pattern::satisfy Parameter Object
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/ParameterObject.php' );

final class MapFilter_Pattern_SatisfyParam
    extends MapFilter_Pattern_ParameterObject
{

  /**
  * Query
  * @var: Array ()
  */
  public $query = Array ();
  
  /**
  * Assertions
  * @var: Array ()
  */
  public $asserts = Array ();
}