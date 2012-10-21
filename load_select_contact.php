<?php
/* File: select_contact_table.php
 * Author: Amr Gaber
 * Created: 14/10/2012
 * Description: Displays a table of contacts selectable by a radio button.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

include( "common.php" );

/* Build query string */
$whereString = "";

/* First Name and Last Name */
if( $_GET[ 'firstName' ] ) {
  $firstname = mysql_real_escape_string( strtolower( $_GET[ 'firstName' ] ) );
  $whereString .= "WHERE contacts.first_name = '" . $firstname . "'";
}

if( $_GET[ 'lastName' ] ) {
  $lastname = mysql_real_escape_string( strtolower( $_GET[ 'lastName' ] ) );

  if( $_GET[ 'firstname' ] ) {
    $whereString .= " AND ";
  } else {
    $whereString .= "WHERE ";
  }

  $whereString .= "contacts.last_name = '" . $lastname . "'";
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Get contact info */
$qs = "SELECT contacts.*,
              contact_phone.*,
              contact_email.email,
              workers.employer
       FROM contacts
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid "
       . $whereString;
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( database_error_alert( mysql_error() ) );
  exit;
}

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th></th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Contact Type</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Cell</th>
        <th>Email</th>
        <th>Employer</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $contact_info = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><input type="radio" name="id" value="<?php echo( $contact_info[ 'id' ] ); ?>"></td>
            <td><?php echo( ucwords( $contact_info[ 'first_name' ] ) ); ?></td>
            <td><?php echo( ucwords( $contact_info[ 'last_name' ] ) ); ?></td>
            <td><?php echo( $contact_info[ 'contact_type' ] ); ?></td>
            <td><?php
              if( $contact_info[ 'street_no' ] ) {
                $address = ucwords( $contact_info[ 'street_no' ] );
    
                if( $contact_info[ 'apt_no' ] ) {
                  $address .= " Apt. " . $contact_info[ 'apt_no' ];
                }
    
                $address .= ", "
                            . ucwords( $contact_info[ 'city' ] )
                            . ", "
                            . strtoupper( $contact_info[ 'state' ] )
                            . " "
                            . $contact_info[ 'zipcode' ];

                echo( $address );
              }
            ?></td>
            <td><?php if( $contact_info[ 'phone' ] != 0 ) { echo( $contact_info[ 'phone' ] ); } ?></td>
            <td><?php if( $contact_info[ 'cell' ] != 0 ) { echo( $contact_info[ 'cell' ] ); } ?></td>
            <td><?php echo( $contact_info[ 'email' ] ); ?></td>
            <td><?php echo( $contact_info[ 'employer' ] ); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else { ?>
  <div class="alert alert-error">No results found for <?php echo( ucwords( $firstname . " " . $lastname ) ); ?>.</div>
<?php } ?>
