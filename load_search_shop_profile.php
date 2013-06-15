<?php
/* File: load_search_shop_profile.php
 * Author: Amr Gaber
 * Created: 2013/1/31
 * Description: Displays a table of shops selectable by a radio button.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* Connect to database */
$mc = connect_to_database();

/* Get workplaces info */
$qs = "SELECT workplaces.*
       FROM workplaces
       ORDER BY workplaces.wname, workplaces.street_no";
       
$qr = execute_query( $qs, $mc ); ?>

<table class="table table-bordered table-striped table-condensed" id="shop-table">
  <thead>
    <tr>
      <th>Workplace Name</th>
      <th>Address</th>
      <th>Phone</th>
      <th>Parent Company</th>
      <th>Total Workers</th>
      <th>BORs</th>
      <th>L&As</th>
      <th>WITs</th>
    </tr>
  </thead>
  
  <tbody>
    <?php while( $sinfo = mysql_fetch_array( $qr ) ) { ?>
      <tr>
        <td><a href="view_shop_profile.php?wid=<?php echo( $sinfo[ 'wid' ] ); ?>" class="shop" data-wid="<?php echo( $sinfo[ 'wid' ] ); ?>"><?php echo( $sinfo[ 'wname' ] ); ?></a></td>
        <td><?php
          $address = $sinfo[ 'street_no' ];

          $address .= ", "
                      . $sinfo[ 'city' ]
                      . ", "
                      . $sinfo[ 'state' ]
                      . " "
                      . $sinfo[ 'zipcode' ]; ?>

          <a href="https://maps.google.com/maps?q=<?php echo( $address ); ?>" target="_blank">
            <?php echo( $address ); ?>
          </a>
        </td>
        <td><a href="tel:<?php echo( $sinfo[ 'phone' ] ); ?>"><?php echo( $sinfo[ 'phone' ] ); ?></a></td>
        <td><?php echo( $sinfo[ 'parent_company' ] ); ?></td>
        <td><?php echo( $sinfo[ 'num_workers' ] ); ?></td>
        <td><?php $qs = "SELECT workers.cid
                         FROM workers
                         WHERE workers.wid = " . $sinfo[ 'wid' ];
            
            $nqr = execute_query( $qs, $mc );
            
            echo( mysql_num_rows( $nqr ) ); ?>
        </td>
        <td><?php $qs = "SELECT contact_action.cid
                         FROM contact_action
                           RIGHT JOIN workers ON contact_action.cid = workers.cid AND workers.wid = " . $sinfo[ 'wid' ] . "
                         WHERE contact_action.aid = 1003";
            
            $nqr = execute_query( $qs, $mc );
            
            echo( mysql_num_rows( $nqr ) ); ?>
        </td>
        <td><?php $qs = "SELECT contact_action.cid
                         FROM contact_action
                           RIGHT JOIN workers ON contact_action.cid = workers.cid AND workers.wid = " . $sinfo[ 'wid' ] . "
                         WHERE contact_action.aid = 1005";
            
            $nqr = execute_query( $qs, $mc );
            
            echo( mysql_num_rows( $nqr ) ); ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
