<?php
/**
* User Tests
*/  

/** Require tested class */
require_once ( __DIR__ . '/../../MapFilter.php' );

class TestUser extends PHPUnit_Framework_TestCase {

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
  
  public static function testSimpleOneWhitelist () {
  
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
    
    $query = Array (
        '-h' => NULL,
        '-v' => NULL,
        '-o' => "a.out"
    );
    
    $filter->setQuery ( $query );

    $result = Array (
        '-h' => NULL
    );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
    
    $query = Array (
        '-o' => "a.out"
    );
    
    $filter->setQuery ( $query );

    self::assertEquals (
        Array (),
        $filter->parse ()
    );
  }

  public static function testSimpleAllWhitelist () {
  
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

    $query = Array (
        '-h' => NULL,
        '-v' => NULL,
        '-o' => "a.out"
    );

    $result = Array (
        '-h' => NULL,
        '-v' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  public static function testSimpleOptWhitelist () {
    
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

    /** Parse all */
    $query = Array (
        '-h' => NULL,
        '-v' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Parse one */
    $query = Array (
        '-v' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Parse one */
    $query = Array (
        '-h' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Parse extra */
    $query = Array (
        '-h' => NULL,
        '-v' => NULL,
        '-o' => "a.out"
    );
    
    $result = Array (
        '-h' => NULL,
        '-v' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
    
    /** Parse nothing */
    $query = Array ();
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
  }

  /** Test parse external source and validate */
  public static function testParseLogin () {
  
    $filter = new MapFilter (
        Test_Source::LOGIN
    );
  
    /** Test Empty */
    $filter->setQuery ( Array () );
    
    self::assertEquals (
        Array (),
        $filter->parse () 
    );
  
    /** Test minimal */
    $query = Array (
      'name' => "me",
      'pass' => "myPass"
    );
    
    $filter->setQuery ( $query );

    self::assertEquals (
        $query,
        $filter->parse ()
    );

    /** Test simple optional */
    $query = Array (
      'name' => "me",
      'pass' => "myPass",
      'use-https' => "yes"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test complex optional */
    $query = Array (
      'name' => "me",
      'pass' => "myPass",
      'remember' => "yes",
      'server' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test all */
    $query = Array (
      'name' => "me",
      'pass' => "myPass",
      'use-https' => "no",
      'remember' => "yes",
      'server' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test Too Much */
    $query = Array (
      'name' => "me",
      'pass' => "myPass",
      'use-https' => "no",
      'remember' => "yes",
      'server' => NULL,
      'user' => NULL
    );
    
    /** More than one option occured first one used */
    $result = Array (
        'name' => "me",
        'pass' => "myPass",
        'use-https' => "no",
        'remember' => "yes",
        'user' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  /** Test parse external source and validate */
  public static function testParseLocation () {
  
    $filter = new MapFilter (
        Test_Source::LOCATION
    );
  
    /** Test Empty */
    $filter->setQuery ( Array () );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Test nick use */
    $query = Array (
        'action' => "delete",
        'nick' => "myLocation"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Coordinates */
    $query = Array (
        'action' => "delete",
        'x' => 1,
        'y' => 1,
        'z' => 2
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Too many directions */
    $query = Array (
        'action' => "delete",
        'x' => 1,
        'y' => 1,
        'z' => 2,
        'a' => 0
    );
    
    $result = Array (
        'action' => "delete",
        'x' => 1,
        'y' => 1,
        'z' => 2
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
    
    /** Too few directions */
    $query = Array (
        'action' => "delete",
        'x' => 1,
        'y' => 1
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Just actions */
    $query = Array (
        'action' => "delete"
    );
    
    $result = Array ();
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
    
    /** Extra argument */
    $query = Array (
        'action' => "delete",
        'nick' => "myLocation",
        'duration' => "permanent"
    );
    
    $result = Array (
        'action' => "delete",
        'nick' => "myLocation",
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
  }
  
  /** Test parse external source and validate */
  public static function testParseAction () {
  
    $filter = new MapFilter (
        Test_Source::ACTION
    );
    
    $filter->setQuery ( Array () );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Wrong action */
    $query = Array (
        'action' => "noSuchAction"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Delete by ID */
    $query = Array (
        'action' => "delete",
        'id' => 42
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Delete by name */
    $query = Array (
        'action' => "delete",
        'file_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Delete by wrong attribute */
    $query = Array (
        'action' => "delete",
        'wrongAttr' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Delete by both */
    $query = Array (
        'action' => "delete",
        'id' => 42,
        'file_name' => "myFile"
    );
    
    $result = Array (
        'action' => "delete",
        'id' => 42
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $result,
        $filter->parse ()
    );
    
    /** Create file with new name */
    $query = Array (
        'action' => "create",
        'new_file' => "fileObj",
        'new_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Create file without name */
    $query = Array (
        'action' => "create",
        'new_file' => "fileObj"
    );
    
    $filter->setQuery ( $query );

    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Rename by ID */
    $query = Array (
        'action' => "rename",
        'id' => 42,
        'new_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );

    /** Rename by name */
    $query = Array (
        'action' => "rename",
        'old_name' => "myFile",
        'new_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Rename without source */
    $query = Array (
        'action' => "rename",
        'new_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Rename without destinations */
    $query = Array (
        'action' => "rename",
        'old_name' => "myFile"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Rename without anything */
    $query = Array (
        'action' => "rename"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Report */
    $query = Array (
        'action' => "report",
        'id' => 42
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Report without id */
    $query = Array (
        'action' => "report"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Report by wrong attribute */
    $query = Array (
        'action' => "report",
        'file_name' => "myName"
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
  }
  
  /** Test parse external source and validate */
  public static function testParseFilterUtility () {

    $filter = new MapFilter (
        Test_Source::FILTER
    );
    
    /** Parse empty */
    $filter->setQuery ( Array () );
    
    self::assertEquals (
        Array (),
        $filter->parse ()
    );
    
    /** Test mandatory */
    $query = Array (
        '-a' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test optional action */
    $query = Array (
        '-c' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test optional actions */
    $query = Array (
        '-c' => NULL,
        '-l' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
    
    /** Test optional actions with optional arg*/
    $query = Array (
        '-c' => NULL,
        '-l' => NULL,
        '-h' => NULL
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->parse ()
    );
  }
}

BaseTest::take ( "TestUser" );
?>
</pre>