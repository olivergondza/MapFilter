<?php

error_reporting ( ( E_ALL | E_STRICT ) );

if ( !is_readable ( '../MapFilter.php' ) ) {

  define ( 'PHP_MAPFILTER_DIR', 'PHP/MapFilter/' );
  define ( 'PHP_MAPFILTER_CLASS', 'PHP/MapFilter.php' );
} else {

  define ( 'PHP_MAPFILTER_DIR', dirname ( __FILE__ ) . '/../MapFilter/' );
  define ( 'PHP_MAPFILTER_CLASS', dirname ( __FILE__ ) . '/../MapFilter.php' );
}

define ( 'PHP_MAPFILTER_TEST_DIR', dirname ( __FILE__ ) );
