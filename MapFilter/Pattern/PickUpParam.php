<?php
/**
* MapFilter_Pattern::pickUp Parameter Object
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/ParameterObject.php' );

final class MapFilter_Pattern_PickUpParam
    extends MapFilter_Pattern_ParameterObject
{

  /**
  * Flags
  * @var: Array ()
  */
  public $flags = Array ();
  
  /**
  * Data
  * @var: Array ()
  */
  public $data = Array ();
}