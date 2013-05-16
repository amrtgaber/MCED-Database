<?php
/* File: load_add_contact_search_table.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Returns add contact search results table for editing an action.
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
$qs = "SELECT contacts.id,
              contacts.first_name,
              contacts.last_name,
              contacts.street_no,
              contacts.apt_no,
              contacts.city,
              contacts.state,
              contacts.zipcode,
              curdate() AS date
       FROM contacts
       WHERE contacts.first_name LIKE '" . $firstname . "%'
         AND contacts.last_name  LIKE '" . $lastname . "%'
       ORDER BY contacts.last_name";

$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed" id="add-contact-table">
    <thead>
      <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Address</th>
        <th>Date Added</th>
        <th style="text-align: center;">Add</th>
      </tr>
    </thead>
    
    <tbody>
      <?php while( $contacts = mysql_fetch_array( $qr ) ) { ?>
        <tr data-id="<?php echo( $contacts[ 'id' ] ); ?>">
          <td><a href="view_contact.php?id=<?php echo( $contacts[ 'id' ] ); ?>" target="_blank"><?php echo( $contacts[ "last_name" ] ); ?></a></td>
          <td><a href="view_contact.php?id=<?php echo( $contacts[ 'id' ] ); ?>" target="_blank"><?php echo( $contacts[ "first_name" ] ); ?></a></td>
          <td><?php if( $contacts[ "apt_no" ] != "" && !is_null( $contacts[ "apt_no" ] ) ) {
              $apt_no = "#" . $contacts[ "apt_no" ];
            } else {
              $apt_no = "";
            }
          
            echo( $contacts[ "street_no" ] . $apt_no . ", " . $contacts[ "city" ] . ", " . $contacts[ "state" ] . " " . $contacts[ "zipcode" ] ); ?>
          </td>
          <td><?php echo( $contacts[ 'date' ] ); ?></td>
          <td style="text-align: center;"><button type="button" class="btn btn-small btn-success add-contact-button" data-id="<?php echo( $contacts[ 'id' ] ); ?>"><i class="icon-plus"></i></button></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } ?>
