<?php
/**
* User Tests
*/  

/** Require tested class */
require_once ( dirname ( __FILE__ ) . '/../../MapFilter.php' );

class TestUser extends BaseTest {

  /** Use utterly wrong pattern */
  public static function testUtterlyWrongPattern () {
    
    try {
      $filter = new MapFilter ( 42 );
    } catch ( MapFilter_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Exception::UNKNOWN_PATTERN_SOURCE,
          $exception->getCode ()
      );
    }
  }
  
  /** Use slightly wrong pattern */
  public static function testWrongPattern () {
    
    try {
      $filter = new MapFilter ( "<lantern></lantern>" );
    } catch ( MapFilter_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  /** Invalid node */
  public static function testWrongTag () {
  
    try {
      $filter = new MapFilter ( "<pattern><wrongnode></wrongnode></pattern>" );
    } catch ( MapFilter_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  /** Invalid attr */
  public static function testWrongAttr () {
  
    $pattern = '
    <pattern>
      <key_attr attr="attrName" wrongattr="wrongAttrName">
        <attr forValue="thisName">thisAttr</attr>
      </key_attr>
    </pattern>
    ';
  
    try {
      $filter = new MapFilter ( $pattern );
    } catch ( MapFilter_Exception $exception ) {

      self::assertEquals (
          MapFilter_Exception::INVALID_PATTERN_ATTRIBUTE,
          $exception->getCode ()
      );
    }
  
  }
  
  public static function provideSimpleOneWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => "a.out" ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-o' => "a.out" ),
            Array ()
        )
    );
  }
  
  /**
  * @dataProvider provideSimpleOneWhitelist
  */
  public static function testSimpleOneWhitelist ( $query, $result ) {
  
    $pattern = "
    <pattern>
      <one>
        <attr>-h</attr>
        <attr>-v</attr>
      </one>
    </pattern>
    ";
    
    $filter = new MapFilter (
        $pattern
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }

  public static function provideSimpleAllWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => "a.out" ),
            Array ( '-h' => NULL, '-v' => NULL )
        )
    );
  }

  /**
  * @dataProvider provideSimpleAllWhitelist
  */
  public static function testSimpleAllWhitelist ( $query, $result ) {
  
    $pattern = "
    <pattern>
      <all>
        <attr>-h</attr>
        <attr>-v</attr>
      </all>
    </pattern>
    ";
    
    $filter = new MapFilter (
        $pattern
    );

    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function provideSimpleOptWhitelist () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL ),
            Array ( '-h' => NULL, '-v' => NULL )
        ),
        Array (
            Array ( '-v' => NULL ),
            Array ( '-v' => NULL )
        ),
        Array (
            Array ( '-h' => NULL ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => "a.out" ),
            Array ( '-h' => NULL, '-v' => NULL )
        )
    );
  }
  
  /**
  * @dataProvider provideSimpleOptWhitelist
  */
  public static function testSimpleOptWhitelist ( $query, $result ) {
    
    $pattern = "
    <pattern>
      <opt>
        <attr>-h</attr>
        <attr>-v</attr>
      </opt>
    </pattern>
    ";
    
    $filter = new MapFilter (
        $pattern
    );

    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }

  public static function provideParseLogin () {
  
    return Array (
        Array (
            Array ( 'name' => "me", 'pass' => "myPass" ),
            Array ( 'name' => "me", 'pass' => "myPass" )
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" )
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL )
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL )
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL, 'user' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'user' => NULL )
        )
    );
  }

  /**
  * Test parse external source and validate
  * @dataProvider provideParseLogin
  */
  public static function testParseLogin ( $query, $result ) {
  
    $filter = new MapFilter (
        Test_Source::LOGIN
    );
  
    /** Test Empty */
    $filter->setQuery ( Array () );
    
    self::assertEquals (
        Array (),
        $filter->parse () 
    );
  }
  
  public static function provideParseLocation () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'nick' => "myLocation" ),
            Array ( 'action' => "delete", 'nick' => "myLocation" )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 ),
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2, 'a' => 0 ),
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1 ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'nick' => "myLocation", 'duration' => "permanent" ),
            Array ( 'action' => "delete", 'nick' => "myLocation" )
        )
    );
  }
  
  /**
  * Test parse external source and validate
  * @dataProvider provideParseLocation
  */
  public static function testParseLocation ( $query, $result ) {
  
    $filter = new MapFilter (
        Test_Source::LOCATION
    );
  
    /** Test Empty */
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function provideParseAction () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'action' => "noSuchAction" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'id' => 42 ),
            Array ( 'action' => "delete", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "delete", 'file_name' => "myFile" ),
            Array ( 'action' => "delete", 'file_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "delete", 'wrongAttr' => NULL ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'id' => 42, 'file_name' => "myFile" ),
            Array ( 'action' => "delete", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "create", 'new_file' => "fileObj", 'new_name' => "myFile" ),
            Array ( 'action' => "create", 'new_file' => "fileObj", 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "create", 'new_file' => "fileObj" ),
            Array ( 'action' => "create", 'new_file' => "fileObj" )
        ),
        Array (
            Array ( 'action' => "rename", 'id' => 42, 'new_name' => "myFile" ),
            Array ( 'action' => "rename", 'id' => 42, 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "rename", 'old_name' => "myFile", 'new_name' => "myFile" ),
            Array ( 'action' => "rename", 'old_name' => "myFile", 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "rename", 'new_name' => "myFile" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "rename", 'old_name' => "myFile" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "rename" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "report", 'id' => 42 ),
            Array ( 'action' => "report", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "report" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "report", 'file_name' => "myName" ),
            Array ()
        )
    );
  }
  
  /**
  * Test parse external source and validate
  * @dataProvider provideParseAction
  */
  public static function testParseAction ( $query, $result ) {
  
    $filter = new MapFilter (
        Test_Source::ACTION
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function provideParseFilterUtility () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( '-a' => NULL ),
            Array ( '-a' => NULL )
        ),
        Array (
            Array ( '-c' => NULL ),
            Array ( '-c' => NULL )
        ),
        Array (
            Array ( '-c' => NULL, '-l' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL )
        ),
        Array (
            Array ( '-c' => NULL, '-l' => NULL, '-h' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL, '-h' => NULL )
        )
    );
  }
  
  /**
  * Test parse external source and validate
  * @dataProvider provideParseFilterUtility
  */
  public static function testParseFilterUtility ( $query, $result ) {

    $filter = new MapFilter (
        Test_Source::FILTER
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function provideParseCoffeMaker () {
  
    return Array (
        Array (
            Array ( 'beverage' => 'coffe' ),
            Array ( 'beverage' => 'coffe', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'tea', 'cup' => 2 ),
            Array ( 'beverage' => 'tea', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'cacao', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'cacao', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffe', 'cup' => 'none', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'coffe', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffe', 'cup' => 'no', 'sugar' => 5 ),
            Array ( 'beverage' => 'coffe', 'cup' => 'no', 'sugar' => 5 )
        )
    );
  }
  
  /**
  * @dataProvider provideParseCoffeMaker
  */
  public static function testParseCoffeMaker ( $query, $result ) {
  
    $filter = new MapFilter (
        Test_Source::COFFE_MAKER
    );
    
    $filter -> setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter -> parse ()
    );
  }
}
?>
