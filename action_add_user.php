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
  alert_error( "You must be logged in to add a user." );
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  alert_error( "You do not have the required privilege level to add a user." );
}

/* Parse and sanitize $_POST[] input */

/* Username */
if( !isset( $_POST[ 'username' ] ) || $_POST[ 'username' ] == "" ) {
  alert_error( "Username is a required field." );
}

if( strlen( $_POST[ 'username' ] ) < 4 ) {
  alert_error( "Username must be at least 4 characters in length." );
}

$username = mysql_real_escape_string( $_POST[ 'username' ] );

/* Password */
if( !isset( $_POST[ 'password' ] ) || $_POST[ 'password' ] == "" ) {
  alert_error( "Password is a required field." );
}

$password = mysql_real_escape_string( $_POST[ 'password' ] );

/* Confirm Password */
if( !isset( $_POST[ 'confirmPassword' ] )
    || $_POST[ 'confirmPassword' ] == ""
    || strcmp( $_POST[ 'confirmPassword' ], $password ) != 0 ) {
  alert_error( "Passwords must match." );
  exit;
}

$password = hash( "sha256", $password );

/* Privilege Level */
if( !isset( $_POST[ 'privilegeLevel' ] ) ) {
  alert_error( "Privilege level is invalid" );
}

$privilege_level = mysql_real_escape_string( $_POST[ 'privilegeLevel' ] );

/* Connect to database */
$mc = connect_to_database();

/* Add to database */
$qs = "INSERT INTO users
      (username, password, privilege_level)
      VALUES ('" . $username . "', '" . $password . "', " . $privilege_level . ")";

$qr = execute_query( $qs, $mc );

/* Return success */ ?>
<div class="alert alert-success">
  <?php echo( $username ); ?> is now a user and can login immediately.
  <button type="button" class="btn btn-success" onclick="parent.hide();">OK</button>
</div>
