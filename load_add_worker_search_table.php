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
              contacts.zipcode
       FROM contacts
       WHERE contacts.first_name LIKE '%" . $firstname . "%'
         AND contacts.last_name  LIKE '%" . $lastname . "%'
       GROUP BY contacts.id
       ORDER BY contacts.last_name, contacts.first_name";

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
        <th style="text-align: center;">WIT</th>
        <th style="text-align: center;">Add</th>
      </tr>
    </thead>
    
    <tbody>
      <?php while( $workers = mysql_fetch_array( $qr ) ) {
        /* get job and rating information */
        $qs = "SELECT contact_sheet.job,
                      contact_sheet.rating
               FROM contact_sheet
               WHERE contact_sheet.cid = " . $workers[ 'id' ] . "
               ORDER BY contact_sheet.cs_date DESC
               LIMIT 1";

        $csqr = execute_query( $qs, $mc );
        $csinfo = mysql_fetch_array( $csqr );
        
        /* get L&A information */
        $qs = "SELECT contact_action.aid
               FROM contact_action
               WHERE contact_action.cid = " . $workers[ 'id' ] . " AND aid = 1003";

        $aqr = execute_query( $qs, $mc );
        
        if( mysql_num_rows( $aqr ) > 0 ) {
          $la = true;
        } else {
          $la = false;
        }
        
        /* get WIT information */
        $qs = "SELECT contact_action.aid
               FROM contact_action
               WHERE contact_action.cid = " . $workers[ 'id' ] . " AND aid = 1005";

        $aqr = execute_query( $qs, $mc );
        
        if( mysql_num_rows( $aqr ) > 0 ) {
          $wit = true;
        } else {
          $wit = false;
        } ?>
          
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
          <td><?php echo( $csinfo[ "job" ] ); ?></td>
            <td style="text-align: center;"><?php echo( $csinfo[ "rating" ] ); ?></td>
            <td style="text-align: center;"><i class="<?php if( $la ) { echo( 'icon-star' ); } ?>"></i></td>
            <td style="text-align: center;"><i class="<?php if( $wit ) { echo( 'icon-star' ); } ?>"></i></td>
          <td style="text-align: center;"><button type="button" class="btn btn-small btn-success add-worker-button" data-id="<?php echo( $workers[ 'id' ] ); ?>"><i class="icon-plus"></i></button></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php
} else {
  alert_error( "No results found." );
}
