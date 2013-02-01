<?php
/* File: load_shop_profile.php
 * Author: Bryan Dorsey
 * Created: 1/31/2013
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
              num_workers
       FROM workplaces
       WHERE wid = " . $id;

$qr = execute_query( $qs, $mc );

$shop_info = mysql_fetch_array( $qr );

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

</table>
