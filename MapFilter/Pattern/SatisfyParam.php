<?php
/**
* MapFilter_Pattern::satisfy Parameter Object
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/../ParameterObject.php' );

final class MapFilter_Pattern_SatisfyParam
    extends MapFilter_ParameterObject
{

  /**
  * In. User query
  * @var Array ( Attribute => Value )
  */
  public $query;
  
  /**
  * Out. Parsing asserts
  * @var Array ( String )
  */
  public $asserts;
  
  /**
  * Out. Parsing flags
  * @var Array ( String )
  */
  public $flags;
}