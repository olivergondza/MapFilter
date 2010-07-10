<?php
/**
* User Tests using login.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Login
*/
class MapFilter_Test_User_TreePattern_Login extends
    PHPUnit_Framework_TestCase
{

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
            Array ( 'login', 'use_https' ),
            Array ()
        ),
        Array (
            Array (
                'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL
            ),
            Array (
                'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL
            ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array (
                'name' => "me", 'pass' => "myPass", 'use-https' => "no",
                'remember' => "yes", 'server' => NULL
            ),
            Array (
                'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'server' => NULL
            ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array (
                'name' => "me", 'pass' => "myPass", 'use-https' => "no",
                'remember' => "yes", 'server' => NULL, 'user' => NULL
            ),
            Array (
                'name' => "me", 'pass' => "myPass", 'remember' => "yes", 'user' => NULL
            ),
            Array ( 'login', 'remember' ),
            Array ()
        ),
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_name' => 'no_name' )
        ),
        Array (
            Array ( 'name' => "me" ),
            Array (),
            Array (),
            Array ( 'no_password' => 'no_password' )
        ),
        Array (
            Array (
                'name' => "me", 'pass' => "myPass", 'use-https' => "yes",
                'remember' => "yes"
            ),
            Array ( 'name' => "me", 'pass' => "myPass", 'use-https' => "yes" ),
            Array ( 'login', 'use_https' ),
            Array ( 'no_remember_method' => 'no_remember_method' )
        )
    );
  }

  /**
   * Test parse external source and validate
   * @dataProvider      provideParseLogin
   */
  public static function testParseLogin ( $query, $result, $flags, $asserts ) {
  
    sort ( $flags );
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::LOGIN
        ),
        $query
    );

    $fFlags = $filter->getFlags ();
    sort ( $fFlags );

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
        $filter->getAsserts ()
    );
  }
}