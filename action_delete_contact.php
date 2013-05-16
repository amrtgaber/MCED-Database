<?php
/* File: action_delete_contact.php
 * Author: Amr Gaber
 * Created: 16/10/2012
 * Description: Handles deleting a contact from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to delete a contact." );
}

/* get id */
$id = mysql_real_escape_string( $_POST[ 'id' ] );

/* Connect to database */
$mc = connect_to_database();

/* delete entry */
$qs = "DELETE contacts.*,
              contact_phone.*,
              contact_email.*,
              workers.*,
              students.*,
              contact_sheet.*
       FROM contacts
         LEFT JOIN contact_phone     ON contacts.id = contact_phone.cid
         LEFT JOIN contact_email     ON contacts.id = contact_email.cid
         LEFT JOIN workers           ON contacts.id = workers.cid
         LEFT JOIN students          ON contacts.id = students.cid
         LEFT JOIN contact_sheet     ON contacts.id = contact_sheet.cid  
       WHERE contacts.id = " . $id;

$qr = execute_query( $qs, $mc ); ?>
