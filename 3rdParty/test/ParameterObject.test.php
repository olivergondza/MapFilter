<?php
/**
* Require tested class
*/
require_once ( dirname ( __FILE__ ) . '/../ParameterObject.php' );

class TestParameterObject extends PHPUnit_Framework_TestCase {  

  /** Call invalid setter and catch an exception */
  public static function testInvalidCall () {
  
     $pa = new ParameterObject ();

     try {
       $pa->noSuchMethod ();
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_CALL,
           $ex->getCode ()
       );
     }
     
     $pa = new ParameterObject ();

     try {
       $pa->set ();
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_CALL,
           $ex->getCode ()
       );
     }
  }
  
  /** Invalid setter */
  public static function testInvalidSetter () {
  
     $pa = new ParameterObject ();

     try {
       $pa->setUnknownValue ( 'val' );
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_SET,
           $ex->getCode ()
       );
     }
  }
  
  /** Invalid getter */
  public static function testInvalidGetter () {
  
     $pa = new ParameterObject ();

     try {
       $pa->getUnknownValue ();
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_GET,
           $ex->getCode ()
       );
     }
  }

  /** Invalid refSetter */
  public static function testInvalidRefSetter () {
  
     $pa = new ParameterObject ();

     try {
       $pa->setRefUnknownValue ( $pa );
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_REFSET,
           $ex->getCode ()
       );
     }
  }

  
  /** Invalid declaration */
  public static function testInvalidDeclaration () {
  
     try {
       $pa = new ParameterObject ( Array ( 0 ) );
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_DECLARATION,
           $ex->getCode ()
       );
     }
  }
  
  /** Invalid arg count */
  public static function testInvalidArgCount () {
  
    $pa = new ParameterObject ();
  
     try {
       
       $pa->setVal ( 1, 2 );
       self::fail ( 'No exception risen' );
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_ARG_COUNT,
           $ex->getCode ()
       );
     }
     
   $pa = new ParameterObject ();
  
     try {
       
       $pa->getVal ( 1, 2 );
       self::fail ( 'No exception risen' );
     } catch ( ParameterObject_Exception $ex ) {
       
       self::assertEquals (
           ParameterObject_Exception::INVALID_ARG_COUNT,
           $ex->getCode ()
       );
     }
     
     $pa = new ParameterObject ();
  
     try {
       
       $pa->setRefVal ( 1, 2 );
       self::fail ( 'No exception risen' );
     } catch ( ParameterObject_Exception $ex ) {

       self::assertEquals (
           ParameterObject_Exception::INVALID_ARG_COUNT,
           $ex->getCode ()
       );
     }
  }

  /** */
  public static function testCreateEmpty () {
  
    $pa = new ParameterObject ();
    
    self::assertFalse ( isset ( $pa->val ) );
  }
  
  /** Create writable object */
  public static function testCreate () {
  
    $val = 'val';
  
    $pa = new ParameterObject (
        Array ( 'val' => $val, 'var' )
    );

    self::assertTrue ( isset ( $pa->val ) );
    self::assertEquals ( 'val', $pa->val );
    
    $pa->val = 'wax';
    self::assertEquals ( 'wax', $pa->val );
    
    
    self::assertFalse ( isset ( $pa->var ) );
    
    $pa->var = 'var';
    self::assertTrue ( isset ( $pa->var ) );
  }
   
  /** test setter/getter usage */
  public static function testSeters () {
    
    $pa = new ParameterObject (
        Array ( 'val', 'var', 'wax' => 'wax' )
    );
    
    self::assertEquals ( 'wax', $pa->getWax () );
    self::assertEquals ( 'wax', $pa->wax );
    
    $pa->setVal ( 'val' );
    
    self::assertEquals ( 'val', $pa->getVal () );
    self::assertEquals ( 'val', $pa->val );
    
    $pa->var = 'var';
    
    self::assertEquals ( 'var', $pa->getVar () );
    self::assertEquals ( 'var', $pa->var );
  }
  
  /** Test fluent setter interface */
  public static function testFluentInterface () {
  
    $val1 = $val2 = 0;
    $pa = new ParameterObject (
        Array ( 'val1' => &$val1, 'val2' => &$val2 )
    );
    
    $pa -> setVal1 ( 1 ) -> setVal2 ( 1 );
    
    self::assertEquals ( 1, $pa->val1 );
    
    self::assertEquals ( 1, $pa->val2 );
    
    /** Objects are equals */
    $val1 = $val2 = 1;
    $new = new ParameterObject (
        Array ( 'val1' => &$val1, 'val2' => &$val2 )
    );
    
    self::assertEquals ( $new, $pa );
  }
  
  /** Test assignment chaining */
  public static function testAssignment () {
  
    $val1 = $val2 = 0;
    $pa = new ParameterObject (
        Array ( 'val1' => &$val1, 'val2' => &$val2 )
    );
    
    $pa->val1 = $var = 1;
    
    self::assertEquals ( 1, $var );
    
    self::assertEquals ( 1, $pa->val1 );
    
    $var = $pa->val2 = 2;
    
    self::assertEquals ( 2, $var );
    
    self::assertEquals ( 2, $pa->val2 );
  }
  
  /** Test inherited object */
  public static function testInherit () {
  
    $pa = new MyParam ();

    self::assertTrue ( isset ( $pa->val ) );

    $pa->val = 'value';
    
    self::assertEquals ( 'value', $pa->val );

    unset ( $pa->val );
  }
  
  /** Test property existence */
  public static function testExistence () {
  
    /** Predeclared */
    $pa = new MyParam ();
    
    self::assertTrue ( $pa->hasProperty ( 'val' ) );
    
    self::assertFalse ( $pa->hasProperty ( 'wax' ) );
    
    /** Injected */
    $pa = new ParameterObject ( Array ( 'wax' ) );
    
    self::assertTrue ( $pa->hasProperty ( 'wax' ) );
    
    self::assertFalse ( $pa->hasProperty ( 'val' ) );
  }
  
  /** test pass by reference and later modification */
  public static function testReferenceByAssign () {
  
    $pa = new ParameterObject (
        Array ( 'var', 'obj' )
    );
    
    $val = 0;
    $obj = new ArrayObject ( Array ( 0 ) );
    
    $pa->var = &$val;
    $pa->obj = &$obj;
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $val = 1;
    $obj = new ArrayObject ( Array ( 1 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->var = 2;
    $pa->obj = new ArrayObject ( Array ( 2 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->setVar ( 3 );
    $pa->setObj ( new ArrayObject ( Array ( 3 ) ) );

    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
  }
  
  /** pass by setRef */
  public static function testReferenceBySetRef () {
    
    $pa = new ParameterObject (
        Array ( 'var', 'obj' )
    );
    
    $val = 0;
    $obj = new ArrayObject ( Array ( 0 ) );
    
    $pa->setRefVar ( &$val );
    $pa->setRefObj ( &$obj );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $val = 1;
    $obj = new ArrayObject ( Array ( 1 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->var = 2;
    $pa->obj = new ArrayObject ( Array ( 2 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->setVar ( 3 );
    $pa->setVar ( new ArrayObject ( Array ( 3 ) ) );

    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
  }
  
  /** pass by constructor */
  public static function testReferenceByConstructor () {
    
    $val = 0;
    $obj = new ArrayObject ( Array ( 0 ) );
    
    $pa = new ParameterObject (
        Array ( 'var' => &$val, 'obj' => &$obj )
    );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $val = 1;
    $obj = new ArrayObject ( Array ( 1 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->var = 2;
    $pa->obj = new ArrayObject ( Array ( 2 ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->setVar ( 3 );
    $pa->setObj ( new ArrayObject ( Array ( 3 ) ) );
    
    self::assertEquals ( $val, $pa->var );
    self::assertEquals ( $obj, $pa->obj );
  }
  
  /** pass by extending constructor */
  public static function testReferenceByExtendedConstructor () {
    
    $val = 0;
    $obj = new ArrayObject ( Array ( 0 ) );
    
    $pa = new ParameterObject (
        Array ( 'val' => &$val, 'obj' => &$obj )
    );
    
    self::assertEquals ( $val, $pa->val );
    self::assertEquals ( $obj, $pa->obj );
    
    $val = 1;
    $obj = new ArrayObject ( Array ( 1 ) );
    
    self::assertEquals ( $val, $pa->val );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->val = 2;
    $pa->obj = new ArrayObject ( Array ( 2 ) );
    
    self::assertEquals ( $val, $pa->val );
    self::assertEquals ( $obj, $pa->obj );
    
    $pa->setVal ( 3 );
    $pa->setVal ( new ArrayObject ( Array ( 3 ) ) );
    
    self::assertEquals ( $val, $pa->val );
    self::assertEquals ( $obj, $pa->obj );
  }
}

final class MyParam extends ParameterObject {

  public $val = 0;
  
  public $obj = NULL;
}
