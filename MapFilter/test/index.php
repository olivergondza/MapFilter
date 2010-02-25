<?php
require_once ( __DIR__ . '/BaseTest.php' );

require_once ( __DIR__ . '/TestPattern.php' );
require_once ( __DIR__ . '/TestMapFilter.php' );
require_once ( __DIR__ . '/TestSerializedPattern.php' );
require_once ( __DIR__ . '/TestUser.php' );

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