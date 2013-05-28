<?php
/* File: load_search_contact.php
 * Author: Amr Gaber
 * Created: 14/10/2012
 * Description: Displays a table of contacts selectable by a radio button.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

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
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid AND main = 1
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid
       LEFT JOIN workplaces    ON workers.wid = workplaces.wid
       WHERE contacts.first_name LIKE '%" . $firstname . "%'
         AND contacts.last_name  LIKE '%" . $lastname . "%'
       GROUP BY contacts.id
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
        while( $cinfo = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><a href="view_contact.php?id=<?php echo( $cinfo[ 'id' ] ); ?>" class="contact"><?php echo( $cinfo[ 'last_name' ] ); ?></a></td>
            <td><a href="view_contact.php?id=<?php echo( $cinfo[ 'id' ] ); ?>" class="contact"><?php echo( $cinfo[ 'first_name' ] ); ?></a></td>
            <td>
              <?php if( $cinfo[ "apt_no" ] != "" && !is_null( $cinfo[ "apt_no" ] ) ) {
                $apt_no = " #" . $cinfo[ "apt_no" ];
              } else {
                $apt_no = "";
              }
            
              echo( $cinfo[ "street_no" ] . $apt_no . ", " . $cinfo[ "city" ] . ", " . $cinfo[ "state" ] . " " . $cinfo[ "zipcode" ] ); ?>
            </td>
            <td><?php echo( $cinfo[ 'phone' ] ); ?></td>
            <td><?php echo( $cinfo[ 'wname' ] ); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found." );
}
