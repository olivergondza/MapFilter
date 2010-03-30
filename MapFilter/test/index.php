<pre><?php

require_once ( 'BaseTest.php' );

error_reporting ( E_ALL | E_STRICT );

/**
* External sources
*/
class Test_Source {
  const LOCATION = "./location.xml";
  const LOGIN = "./login.xml";
  const ACTION = "./action.xml";
  const FILTER = "./filter.xml";
}

BaseTest::takeDir (
    dirname ( __FILE__ )
);
?>
</pre>