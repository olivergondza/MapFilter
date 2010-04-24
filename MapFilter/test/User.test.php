<?php
/**
* User Tests
*/  

/** Require tested class */
require_once ( dirname ( __FILE__ ) . '/../../MapFilter.php' );

class TestUser extends PHPUnit_Framework_TestCase {

  public static function testEmptyPattern  () {
  
    $query = Array ( 'attr' => 'value' );
    $filter = new MapFilter ();
    $filter->setQuery (
        $query
    );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
  }

  /** Use slightly wrong pattern */
  public static function testWrongPattern () {
    
    try {
      $pattern = MapFilter_Pattern::load ( "<lantern></lantern>" );
    } catch ( MapFilter_Pattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  /** Invalid node */
  public static function testWrongTag () {
  
    try {
      $pattern = MapFilter_Pattern::load ( "<pattern><wrongnode></wrongnode></pattern>" );
    } catch ( MapFilter_Pattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  /** Multiple tree deserialization */
  public static function testMultipleTree () {
  
    try {
      $pattern = MapFilter_Pattern::load ( "<pattern><opt></opt><all></all></pattern>" );
    } catch ( MapFilter_Pattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_Pattern_Exception::TOO_MANY_PATTERNS,
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
      $filter = new MapFilter ( MapFilter_Pattern::load ( $pattern ) );
    } catch ( MapFilter_Pattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ATTRIBUTE,
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
        MapFilter_Pattern::load ( $pattern )
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
        MapFilter_Pattern::load ( $pattern )
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
        MapFilter_Pattern::load ( $pattern )
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
            Array ( 'name' => "me", 'pass' => "myPass" ),
            Array ( 'login' ),
            Array ()
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" ),
            Array ( 'login' ),
            Array ()
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'server' => NULL, 'user' => NULL ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "no", 'remember' => "yes", 'user' => NULL ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_name' )
        ),
        Array (
            Array ( 'name' => "me" ),
            Array (),
            Array (),
            Array ( 'no_password' )
        ),
        Array (
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes", 'remember' => "yes" ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" ),
            Array ( 'login' ),
            Array ( 'no_remember_method' )
        )
    );
  }

  /**
  * Test parse external source and validate
  * @dataProvider provideParseLogin
  */
  public static function testParseLogin ( $query, $result, $flags, $asserts ) {
  
    sort ( $flags );
    sort ( $asserts );
  
    $filter = new MapFilter (
        MapFilter_Pattern::fromFile ( Test_Source::LOGIN )
    );

    /** Test Empty */
    $filter->setQuery ( $query );

    $fResult = $filter->parse ();
    $fFlags = $filter->getFlags ();
    $fAsserts = $filter->getAsserts ();
    sort ( $fFlags );
    sort ( $fAsserts );

    self::assertEquals (
        $result,
        $fResult
    );

    self::assertEquals (
        $flags,
        $fFlags
    );

    self::assertEquals (
        $asserts,
        $fAsserts
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
        MapFilter_Pattern::fromFile ( Test_Source::LOCATION )
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
        MapFilter_Pattern::fromFile ( Test_Source::ACTION )
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
        MapFilter_Pattern::fromFile ( Test_Source::FILTER )
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function provideParseCoffeeMaker () {
  
    return Array (
        Array (
            Array ( 'beverage' => 'coffee' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
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
            Array ( 'beverage' => 'coffee', 'cup' => 'none', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        )
    );
  }
  
  /**
  * @dataProvider provideParseCoffeeMaker
  */
  public static function testParseCoffeeMaker ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_Pattern::fromFile ( Test_Source::COFFEE_MAKER )
    );
    
    $filter -> setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter -> parse ()
    );
  }
  
  public static function provideDuration () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_hour' )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0 ),
            Array (),
            Array (),
            Array ( 'no_duration_hour', 'no_end_hour', 'no_termination_time' )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 'now' ),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_second' )
        ),
        Array (
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59,
            ),
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59,
            ),
            Array ( 'duration', 'ending_time' ),
            Array ()
        ),
        Array (
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59,
            ),
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59,
            ),
            Array ( 'duration', 'duration_time' ),
            Array ( 'no_end_hour' )
        ),
        Array (
            Array ( 'start_hour' => -1 ),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_hour' )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 60 ),
            Array (),
            Array (),
            Array ( 'no_beginning_time', 'no_start_minute' )
        )
    );
  }
  
  /**
  * @dataProvider provideDuration
  */
  public static function testDuration ( $query, $result, $flags, $asserts ) {
  
    sort ( $flags );
    sort ( $asserts );
  
    $filter = new MapFilter (
        MapFilter_Pattern::fromFile ( Test_Source::DURATION ),
        $query
    );
    
    $filter->parse ();
    
    $fFlags = $filter->getFlags ();
    $fAsserts = $filter->getAsserts ();
    sort ( $fFlags );
    sort ( $fAsserts );

    self::assertEquals (
        $result,
        $filter->getResults ()
    );
    
    self::assertEquals (
        $flags,
        $fFlags
    );
    
    self::assertEquals (
        $asserts,
        $fAsserts
    );
  }
}
