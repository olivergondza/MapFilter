<pre><?php

require_once ( 'PHPUnit/Framework.php' );
require_once ( 'PHPUnit/Framework/IncompleteTestError.php' );
require_once ( 'PHPUnit/Framework/TestCase.php' );
require_once ( 'PHPUnit/Framework/TestSuite.php' );
require_once ( 'PHPUnit/Runner/Version.php' );
require_once ( 'PHPUnit/TextUI/TestRunner.php' );
require_once ( 'PHPUnit/Util/Filter.php' );
  
class BaseTest extends PHPUnit_Framework_TestCase {

  /**
  * Take a test
  * @suiteName: String
  */
  public static function take ( $suiteName ) {
    echo ( "<p><strong>$suiteName</strong></p>\n" );

    PHPUnit_TextUI_TestRunner::run ( self::makeSuite ( $suiteName ) );
    return;
  }
  
  /**
  * Create a suite to test
  * @returns: PHPUnit_Framework_TestSuite
  */
  public static function makeSuite ( $suiteName ) {
    $suite = new PHPUnit_Framework_TestSuite ();

    $suite->addTestSuite ( $suiteName );
    
    return $suite;
  }
  
  /**
  * Take all tests in directory
  * Test class has to starts with "Test"
  * @path: String; Path to directory to test
  */
  public static function testDir ( $path ) {
    
    return;
  }
}