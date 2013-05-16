<?php
/* File: load_sidebar.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Loads the sidebar logo.
 */
 
include( 'common.php' );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}
?>

<img src="img/kc99-logo-9-27.png">
