<?php
/* File: add_user_action.php
 * Author: Amr Gaber
 * Created: 11/10/2012
 * Description: Handles adding user into KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !isset( $_SESSION[ 'username' ] ) ) {
  echo( "Unauthorized" );
  exit;
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  echo( "Permission Denied" );
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Username */
if( !isset( $_POST[ 'username' ] ) || $_POST[ 'username' ] == "" ) {
  echo( "Invalid Username" );
  exit;
}

if( strlen( $_POST[ 'username' ] ) < 4 ) {
  echo( "Invalid Username Length" );
  exit;
}

$username = mysql_real_escape_string( $_POST[ 'username' ] );

/* Password */
if( !isset( $_POST[ 'password' ] ) || $_POST[ 'password' ] == "" ) {
  echo( "Invalid Password" );
  exit;
}

$password = mysql_real_escape_string( $_POST[ 'password' ] );

/* Confirm Password */
if( !isset( $_POST[ 'confirmPassword' ] )
    || $_POST[ 'confirmPassword' ] == ""
    || strcmp( $_POST[ 'confirmPassword' ], $password ) != 0 ) {
  echo( "Invalid Confirm Password" );
  exit;
}

$password = hash( "sha256", $password );

/* Privilege Level */
if( !isset( $_POST[ 'privilegeLevel' ] ) ) {
  echo( "Invalid Privilege Level" );
  exit;
}

$privilege_level = mysql_real_escape_string( $_POST[ 'privilegeLevel' ] );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
mysql_select_db( "kc99_data" );

/* Add to database */
$qs = "INSERT INTO users
      (username, password, privilege_level)
      VALUES ('" . $username . "', '" . $password . "', " . $privilege_level . ")";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error " . mysql_error() );
  exit;
}

/* Return success */
echo( "Success" );

?>

