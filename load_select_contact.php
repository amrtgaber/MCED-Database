<?php
/* File: load_select_contact.php
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

include( "db_credentials.php" );
include( "common.php" );

/* First Name and Last Name */
$firstname = mysql_real_escape_string( strtolower( $_GET[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_GET[ 'lastName' ] ) );

/* Connect to database */
$mc = connect_to_database();

/* Get contact info */
$qs = "SELECT contacts.*,
              contact_phone.*,
              contact_email.email,
              workplaces.wname
       FROM contacts
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid
       LEFT JOIN workplaces    ON workers.wid = workplaces.wid
       WHERE contacts.first_name LIKE '" . $firstname . "%'
         AND contacts.last_name  LIKE '" . $lastname . "%'
       ORDER BY contacts.last_name";

$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Employer</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $contact_info = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><a href="javascript:void;" class="contact" data-id="<?php echo( $contact_info[ 'id' ] ); ?>"><?php echo( $contact_info[ 'last_name' ] ); ?></a></td>
            <td><a href="javascript:void;" class="contact" data-id="<?php echo( $contact_info[ 'id' ] ); ?>"><?php echo( $contact_info[ 'first_name' ] ); ?></a></td>
            <td><?php if( $workers[ "apt_no" ] != "" && !is_null( $workers[ "apt_no" ] ) ) {
                $apt_no = "#" . $workers[ "apt_no" ];
              } else {
                $apt_no = "";
              }
            
              echo( $workers[ "street_no" ] . $apt_no . ", " . $workers[ "city" ] . ", " . $workers[ "state" ] . " " . $workers[ "zipcode" ] ); ?></td>
            <td><?php if( $contact_info[ 'phone' ] != 0 ) { echo( $contact_info[ 'phone' ] ); } ?></td>
            <td><?php echo( $contact_info[ 'wname' ] ); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found for " . $firstname . " " . $lastname . "." );
}
