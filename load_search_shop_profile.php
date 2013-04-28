<?php
/* File: load_search_shop_profile.php
 * Author: Amr Gaber
 * Created: 2013/1/31
 * Description: Displays a table of shops selectable by a radio button.
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

/* Search for workplace */
$wname = mysql_real_escape_string( $_GET[ 'wname' ] );

$qs = "SELECT workplaces.*
       FROM workplaces
       WHERE wname LIKE '" . $wname . "%'
       ORDER BY workplaces.wname";
       
$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th>Workplace Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Contacted Workers</th>
        <th>L&As</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $sinfo = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><a href="view_shop_profile.php?id=<?php echo( $sinfo[ 'wid' ] ); ?>" class="shop" data-wid="<?php echo( $sinfo[ 'wid' ] ); ?>"><?php echo( $sinfo[ 'wname' ] ); ?></a></td>
            <td><?php
              $address = $sinfo[ 'street_no' ];
    
              $address .= ", "
                          . $sinfo[ 'city' ]
                          . ", "
                          . $sinfo[ 'state' ]
                          . " "
                          . $sinfo[ 'zipcode' ];

              echo( $address ); ?>
            </td>
            <td><?php echo( $sinfo[ 'phone' ] ); ?></td>
            <td><?php /* TODO: contacted workers */ ?></td>
            <td><?php /* TODO: L&As */ ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found." );
}
