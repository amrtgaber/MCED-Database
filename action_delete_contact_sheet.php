<?php
/* File: action_delete_contact_sheet.php
 * Author: Amr Gaber
 * Created: 2013/4/28
 * Description: Handles deleting a contact sheet from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to delete a contact sheet." );
}

/* get csid */
$csid = mysql_real_escape_string( $_POST[ 'csid' ] );

/* Connect to database */
$mc = connect_to_database();

/* delete entry */
$qs = "DELETE
       FROM contact_sheet
       WHERE contact_sheet.id = " . $csid;

$qr = execute_query( $qs, $mc ); ?>
