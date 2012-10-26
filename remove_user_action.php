<?php
/* File: remove_user_action.php
 * Author: Amr Gaber
 * Created: 19/10/2012
 * Description: Handles removing a user from the KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  echo( "Permission Denied" );
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Get username */
if( !isset( $_POST[ 'username' ] ) ) {
  echo( "Invalid Username" );
  exit;
}

$username = mysql_real_escape_string( $_POST[ 'username' ] );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
mysql_select_db( "kc99_data" );

/* Search for entry */
$qs = "DELETE
       FROM users
       WHERE username = '" . $username . "'";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

echo( "Success" );

?>
