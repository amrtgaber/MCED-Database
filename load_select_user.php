<?php
/* File: load_select_user.php
 * Author: Amr Gaber
 * Created: 19/10/2012
 * Description: Table containing a radio list of all users in KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) { ?>
  <div class="alert alert-error">You do not have the required privilege level to manage users.</div>
  <?php exit;
}

include( "common.php" );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
mysql_select_db( "kc99_data" );

/* Get users */
$qs = "SELECT username, privilege_level
       FROM users";

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( database_error_alert( mysql_error() ) );
  exit;
}
?>

<table class="table table-bordered table-striped table-condensed">
  <thead>
    <tr>
      <th width="20"></th>
      <th>Username</th>
    </tr>
  </thead>

  <tbody>
    <?php while( $user_info = mysql_fetch_array( $qr ) ) {
      if( strcmp( $user_info[ 'username' ], 'root' ) == 0 || strcmp( $user_info[ 'username' ], 'bdorsey' ) == 0 ) {
        continue;
      } ?>
      <tr>
        <td width="20"><input type="radio" name="username" value="<?php echo( $user_info[ 'username' ] ) ?>"></td>
        <td><?php echo( $user_info[ 'username' ] ) ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
