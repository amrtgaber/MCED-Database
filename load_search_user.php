<?php
/* File: load_search_user.php
 * Author: Amr Gaber
 * Created: 2012/10/19
 * Description: Table containing a list of all users in KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* Connect to database */
$mc = connect_to_database();

/* Get users */
$qs = "SELECT id,
              username
       FROM users
       ORDER BY username";

$qr = execute_query( $qs, $mc ); ?>

<table class="table table-bordered table-striped table-condensed" id="user-table">
  <thead>
    <tr>
      <th>Username</th>
      <th>Turf</th>
      <th>WITs</th>
    </tr>
  </thead>
  
  <tbody>
    <?php while( $uinfo = mysql_fetch_array( $qr ) ) { ?>
      <tr>
        <td><a href="view_user.php?uid=<?php echo( $uinfo[ 'id' ] ); ?>" class="user"><?php echo( $uinfo[ 'username' ] ); ?></a></td>
        <td></td>
        <td></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
