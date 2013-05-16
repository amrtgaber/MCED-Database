<?php
/* File: action_delete_user.php
 * Author: Amr Gaber
 * Created: 2013/5/1
 * Description: Handles deleting a user from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to remove a user." );
}

/* get id */
$uid = mysql_real_escape_string( $_POST[ 'uid' ] );

/* Connect to database */
$mc = connect_to_database();

/* Search for entry */
$qs = "DELETE
       FROM users
       WHERE id = " . $uid;

$qr = execute_query( $qs, $mc ); ?>
