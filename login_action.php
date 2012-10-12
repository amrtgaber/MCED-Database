<?php
/* File: login_action.php
 * Author: Amr Gaber
 * Created: 28/09/2012
 * Description: Handles logging in users for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

$username = mysql_real_escape_string( $_POST[ 'username' ] );
$password = hash( "sha256", $_POST[ 'password' ] );

$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

$qs = "SELECT username, privilege_level
       FROM users
       WHERE username='" . $username . "' AND password='" . $password . "'";
$qr = mysql_query( $qs, $mc );

$row = mysql_fetch_array( $qr );

if( $username ) {
  $_SESSION[ 'username' ] = $row[ 'username' ];
  $_SESSION[ 'privilege_level' ] = $row[ 'privilege_level' ];
  header( "HTTP/1.1 200 OK" );
} else {
  header( "HTTP/1.1 401 Unauthorized" );
}

?>
