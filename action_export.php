<?php
/* File: action_export.php
 * Author: Amr Gaber
 * Created: 06/03/2014
 * Description: Exports info as CSV (name, phone, email).
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a contact." );
}

/* Must have proper privilege level */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  alert_error( "You don't have the required privileges to perform an export." );
}

/* connect to database */
$mc = connect_to_database();

$qs = "SELECT contacts.first_name,
              contacts.last_name,
              contact_phone.phone,
              contact_email.email 
       INTO OUTFILE " . $of . " " . "
       FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
       ESCAPED BY '\\\\' LINES TERMINATED BY '\\\n'
       FROM contacts
         LEFT JOIN contact_phone  ON contacts.id = contact_phone.cid
         LEFT JOIN contact_email  ON contacts.id = contact_email.cid
       ORDER BY contacts.last_name";

$qr = execute_query( $qs, $mc );

$trimof = str_replace( "'", '', $of );
if( file_exists( $trimof ) ) {
  header( "Content-type: text/csv" );
  header( "Content-description: File Transfer" );
  header( "Content-disposition: attachment; filename=" . $csvfn );
  header( "Pragma: public" );
  header( "Cache-control: max-age=0" );
  header( "Expires: 0" );
  header( "Content-Length:" . filesize( $trimof ) );
  ob_clean();
  flush();
  readfile( $trimof );
} else {
  alert_error ( "File not found" );
}

?>
