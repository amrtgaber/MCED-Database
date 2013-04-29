<?php
/* File: load_search_action.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Displays a table of actions.
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
$aname = mysql_real_escape_string( $_GET[ 'aname' ] );

$qs = "SELECT actions.*
       FROM actions
       WHERE aname LIKE '" . $aname . "%'
       ORDER BY actions.aname";

$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th>Action Name</th>
        <th>Number of Contacts</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $ainfo = mysql_fetch_array( $qr ) ) { ?>
          <tr>
            <td><a href="view_action.php?aid=<?php echo( $ainfo[ 'aid' ] ); ?>" class="action" data-aid="<?php echo( $ainfo[ 'aid' ] ); ?>"><?php echo( $ainfo[ 'aname' ] ); ?></a></td>
            <td><?php /* TODO: Number of Contacts */ ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found." );
}
