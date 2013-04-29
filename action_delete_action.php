<?php
/* File: action_delete_action.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Handles deleting an action from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to delete a shop profile." );
}

/* Must have privilege level of 3 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 3 ) {
  alert_error( "You do not have the required privilege level to delete an action." );
}

/* get aid */
$aid = mysql_real_escape_string( $_POST[ 'aid' ] );

/* Connect to database */
$mc = connect_to_database();

/* delete entry */
$qs = "DELETE actions.*,
              contact_action.*
       FROM actions
         LEFT JOIN contact_action ON actions.aid = contact_action.aid
       WHERE actions.aid = " . $aid;

$qr = execute_query( $qs, $mc ); ?>
