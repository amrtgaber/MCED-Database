<?php
/* File: remove_contact_action.php
 * Author: Amr Gaber
 * Created: 16/10/2012
 * Description: Handles removing a contact from the KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Must have privilege level of 3 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 3 ) {
  echo( "Permission Denied" );
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Get ID */
if( !isset( $_POST[ 'id' ] ) ) {
  echo( "Invalid ID" );
  exit;
}

$id = mysql_real_escape_string( $_POST[ 'id' ] );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Search for entry */
$qs = "DELETE contacts.*,
              contact_phone.*,
              contact_email.*,
              workers.*,
              students.*,
              contact_organizer.*
       FROM contacts
       LEFT JOIN contact_phone     ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email     ON contacts.id = contact_email.cid
       LEFT JOIN workers           ON contacts.id = workers.cid
       LEFT JOIN students          ON contacts.id = students.cid
       LEFT JOIN contact_organizer ON contacts.id = contact_organizer.cid 
       WHERE contacts.id = " . $id;
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

echo( "Success" );

?>
