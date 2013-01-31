<?php
/* File: load_select_shop_profile.php
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

/* Get search string */
if( $_GET[ 'wname'] ) {
  $wname = mysql_real_escape_string( $_GET[ 'wname' ] );

  $qs = "SELECT workplaces.*
         FROM workplaces
         WHERE wname = '" . $wname . "'
         ORDER BY workplaces.wname";
} else {
  $qs = "SELECT workplaces.*
         FROM workplaces
         ORDER BY workplaces.wname";
}

/* Search for workplace */
$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th></th>
        <th>Workplace Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>CEO</th>
        <th>Parent Company</th>
        <th>Total Workers</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $shop_info = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><input type="radio" name="id" value="<?php echo( $shop_info[ 'wid' ] ); ?>"></td>
            <td id="wname<?php echo( $shop_info[ 'wid' ] ); ?>"><?php echo( $shop_info[ 'wname' ] ); ?></td>
            <td><?php
              $address = $shop_info[ 'street_no' ];
    
              $address .= ", "
                          . $shop_info[ 'city' ]
                          . ", "
                          . $shop_info[ 'state' ]
                          . " "
                          . $shop_info[ 'zipcode' ];

              echo( $address );
            ?></td>
            <td><?php echo( $shop_info[ 'phone' ] ); ?></td>
            <td><?php echo( $shop_info[ 'ceo' ] ); ?></td>
            <td><?php echo( $shop_info[ 'parent_company' ] ); ?></td>
            <td><?php echo( $shop_info[ 'num_workers' ] ); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found for " . $wname . "." );
}
