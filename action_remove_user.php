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
  alert_error( "You must be logged in to remove a user." );
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  alert_error( "You do not have the required privilege level to remove a user." );
}

/* Parse and sanitize $_POST[] input */

/* Get username */
if( !isset( $_POST[ 'username' ] ) ) {
  alert_error( "Username is a required field." );
}

$username = mysql_real_escape_string( $_POST[ 'username' ] );

/* Connect to database */
$mc = connect_to_database();

/* Search for entry */
$qs = "DELETE
       FROM users
       WHERE username = '" . $username . "'";

$qr = execute_query( $qs, $mc );

/* Return success */ ?>
<div class="alert alert-success">
  The user <?php echo( $username ); ?> was successfully removed.
  <button type="button" class="btn btn-success" onclick="parent.hide();">OK</button>
</div>
