<?php
/* File: action_delete_action.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Handles deleting an action from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to delete a shop profile." );
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
