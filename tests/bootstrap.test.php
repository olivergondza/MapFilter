<?php

error_reporting ( ( E_ALL | E_STRICT ) );

set_include_path (
    dirname ( dirname ( __FILE__ ) )
    . PATH_SEPARATOR . get_include_path ()
);

define ( 'PHP_MAPFILTER_DIR', 'PHP/MapFilter/' );
define ( 'PHP_MAPFILTER_CLASS', 'PHP/MapFilter.php' );

define ( 'PHP_MAPFILTER_TEST_DIR', 'PHP/MapFilter/tests' );
