<?php
/* File: load_add_worker_search_table.php
 * Author: Amr Gaber
 * Created: 2013/4/23
 * Description: Returns add worker search results table for modify shop profile.
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
              contact_sheet.job,
              contact_sheet.rating,
              MAX( contact_action.aid ) AS aid
       FROM contacts
         LEFT JOIN contact_sheet  ON contacts.id = contact_sheet.cid
         LEFT JOIN contact_action ON contacts.id = contact_action.cid
       WHERE contacts.first_name LIKE '%" . $firstname . "%'
         AND contacts.last_name  LIKE '%" . $lastname . "%'
       GROUP BY contacts.id
       ORDER BY contacts.last_name";

$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed" id="add-worker-table">
    <thead>
      <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Address</th>
        <th>Job</th>
        <th style="text-align: center;">Rating</th>
        <th style="text-align: center;">L&A</th>
        <th style="text-align: center;">Add</th>
      </tr>
    </thead>
    
    <tbody>
      <?php while( $workers = mysql_fetch_array( $qr ) ) { ?>
        <tr data-id="<?php echo( $workers[ 'id' ] ); ?>">
          <td><a href="view_contact.php?id=<?php echo( $workers[ 'id' ] ); ?>" target="_blank"><?php echo( $workers[ "last_name" ] ); ?></a></td>
          <td><a href="view_contact.php?id=<?php echo( $workers[ 'id' ] ); ?>" target="_blank"><?php echo( $workers[ "first_name" ] ); ?></a></td>
          <td><?php if( $workers[ "apt_no" ] != "" && !is_null( $workers[ "apt_no" ] ) ) {
              $apt_no = "#" . $workers[ "apt_no" ];
            } else {
              $apt_no = "";
            }
          
            echo( $workers[ "street_no" ] . $apt_no . ", " . $workers[ "city" ] . ", " . $workers[ "state" ] . " " . $workers[ "zipcode" ] ); ?>
          </td>
          <td><?php echo( $workers[ "job" ] ); ?></td>
          <td style="text-align: center;"><?php echo( $workers[ "rating" ] ); ?></td>
          <td style="text-align: center;"><i class="<?php if( $workers[ 'aid' ] == 1003 ) { echo( 'icon-star' ); } ?>"></i></td>
          <td style="text-align: center;"><button type="button" class="btn btn-small btn-success add-worker-button" data-id="<?php echo( $workers[ 'id' ] ); ?>"><i class="icon-plus"></i></button></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php
} else {
  alert_error( "No results found." );
}
