<?php
/* File: load_shop_profile.php
 * Author: Bryan Dorsey
 * Created: 1/31/2013
 * Modified: 2/17/2013
 * Modified By: Bryan Dorsey
 * Description: Returns the shop profile for KC99 database.
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

/* Connect to database */
$mc = connect_to_database();

/* If id is present, populate form. */
if( !isset( $_GET[ 'id' ] ) ) {
  alert_error( "No shop selected." );
}
  
$id = mysql_real_escape_string( $_GET[ 'id' ] );

/* Get shop information */
$qs = "SELECT wname,
              street_no,
              city,
              state,
              zipcode,
              ceo,
              parent_company,
              phone,
              num_workers,
              wnotes
       FROM workplaces
       WHERE wid = " . $id;

$qr = execute_query( $qs, $mc );

$shop_info = mysql_fetch_array( $qr );

/*Get corresponding workers for shop*/
$qs = "SELECT workers.cid,
              workers.wid,
              contacts.first_name,
              contacts.last_name,
              contacts.street_no,
              contacts.city,
              contacts.state,
              contacts.zipcode,
              contact_sheet.job,
              contact_sheet.rating,
              MAX( contact_action.aid ) AS aid
       FROM workers 
         LEFT JOIN contacts       ON workers.cid = contacts.id
         LEFT JOIN contact_sheet  ON workers.cid = contact_sheet.cid
         LEFT JOIN contact_action ON workers.cid = contact_action.cid
       WHERE wid = " . $id . "
       GROUP BY workers.cid
       ORDER BY contacts.last_name";

$qr = execute_query($qs, $mc);

?>

<h2><?php echo( $shop_info[ 'wname' ] ); ?></h2>

<table class="table table-hover">
  <tr>
    <td class="info-label">Address</td>
    <td><?php echo( $shop_info[ 'street_no' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">City</td>
    <td><?php echo( $shop_info[ 'city' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">State</td>
    <td><?php echo( $shop_info[ 'state' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Zip Code</td>
    <td><?php echo( $shop_info[ 'zipcode' ] ); ?></td>
  </tr>
  
  <tr>
    <td class="info-label">Phone</td>
    <td><?php
      if( $shop_info[ 'phone' ] != 0 ) {
        if( strlen( $shop_info[ 'phone' ] ) == 10 ) {
          /* Area code and phone number */
          echo( "(" . substr( $shop_info[ 'phone' ], 0, 3 ) . ") "
                . substr( $shop_info[ 'phone' ], 3, 3 ) . "-"
                . substr( $shop_info[ 'phone' ], 6 ) );
        } else {
          /* Only phone number */
          echo( substr( $shop_info[ 'phone' ], 0, 3 ) . "-"
                . substr( $shop_info[ 'phone' ], 3 ) );
        }
      } ?></td>
  </tr>

  <tr>
    <td class="info-label">Ceo</td>
    <td><?php echo( $shop_info[ 'ceo' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Parent Company</td>
    <td><?php echo( $shop_info[ 'parent_company' ] ); ?></td>
  </tr>  

  <tr>
    <td class="info-label">Number of Workers</td>
    <td><?php echo( $shop_info[ 'num_workers' ] ); ?></td>
  </tr>
  
  <tr>
    <td class="info-label">Notes</td>
    <td><?php echo( $shop_info[ 'wnotes' ] ); ?></td>
  </tr>
  
  <tr>
    <td class="info-label">Contacted Workers</td>
    <td>
      <table class="table table-bordered table-striped table-condensed">
        <thead>
          <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Address</th>
            <th>Job</th>
            <th>Rating</th>
            <th>L&A</th>
          </tr>
        </thead>
        <tbody>
        <?php while( $workers = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><a href="search_contact.php?id=<?php echo( $workers[ 'cid' ] ); ?>"><?php echo( $workers[ "last_name" ] ); ?></a></td>
            <td><a href="search_contact.php?id=<?php echo( $workers[ 'cid' ] ); ?>"><?php echo( $workers[ "first_name" ] ); ?></a></td>
            <td><?php echo( $workers[ "street_no" ] . " " . $workers[ "city" ] ); ?></td>
            <td><?php echo( $workers[ "job" ] ); ?></td>
            <td><?php echo( $workers[ "rating" ] ); ?></td>
            <td><i class="<?php if( $workers[ 'aid' ] == 1003 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </td>
  </tr>

</table>
