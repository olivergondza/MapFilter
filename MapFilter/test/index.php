<?php

require_once ( dirname ( __FILE__ ) . '/BaseTest.php' );

require_once ( dirname ( __FILE__ ) . '/TestPattern.php' );
require_once ( dirname ( __FILE__ ) . '/TestMapFilter.php' );
require_once ( dirname ( __FILE__ ) . '/TestSerializedPattern.php' );
require_once ( dirname ( __FILE__ ) . '/TestUser.php' );

/**
* External sources
*/
class Test_Source {
  const LOCATION = "./location.xml";
  const LOGIN = "./login.xml";
  const ACTION = "./action.xml";
  const FILTER = "./filter.xml";
}
?>