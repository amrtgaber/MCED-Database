<?php
/* File: remove_contact_action.php
 * Author: Amr Gaber
 * Created: 16/10/2012
 * Description: Handles removing a contact from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to remove a contact." );
}

/* Must have privilege level of 3 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 3 ) {
  alert_error( "You do not have the required privilege level to remove a contact." );
}

/* Parse and sanitize $_POST[] input */

/* Get ID */
if( !isset( $_POST[ 'id' ] ) ) {
  alert_error( "ID is invalid." );
}

$id = mysql_real_escape_string( $_POST[ 'id' ] );

/* Connect to database */
$mc = connect_to_database();

$qs = "SELECT contacts.first_name,
              contacts.last_name
       FROM contacts
       WHERE contacts.id = " . $id;

$qr = execute_query( $qs, $mc );

$contact_info = mysql_fetch_array( $qr );

$name = ucwords( $contact_info[ 'first_name' ] . " " . $contact_info[ 'last_name' ] );

/* delete entry */
$qs = "DELETE contacts.*,
              contact_phone.*,
              contact_email.*,
              workers.*,
              students.*,
              contact_organizer.*,
              contact_sheet.*
       FROM contacts
       LEFT JOIN contact_phone     ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email     ON contacts.id = contact_email.cid
       LEFT JOIN workers           ON contacts.id = workers.cid
       LEFT JOIN students          ON contacts.id = students.cid
       LEFT JOIN contact_organizer ON contacts.id = contact_organizer.cid
       LEFT JOIN contact_sheet     ON contacts.id = contact_sheet.cid  
       WHERE contacts.id = " . $id;
$qr = execute_query( $qs, $mc );

/* Return success */ ?>
<div class="alert alert-success">
  The contact <?php echo( $name ); ?> was successfully removed.
  <button type="button" class="btn btn-success" onclick="$( this ).parent().hide();">OK</button>
</div>
