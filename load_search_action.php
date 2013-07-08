<?php
/* File: load_search_action.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Displays a table of actions.
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

/* Get action info */
$qs = "SELECT actions.*
       FROM actions
       ORDER BY actions.aname";

$qr = execute_query( $qs, $mc ); ?>

<table class="table table-bordered table-striped table-condensed" id="action-table">
  <thead>
    <tr>
      <th>Action Name</th>
      <th>Number of Contacts</th>
    </tr>
  </thead>
  
  <tbody>
    <?php while( $ainfo = mysql_fetch_array( $qr ) ) { ?>
      <tr>
        <td><a href="view_action.php?aid=<?php echo( $ainfo[ 'aid' ] ); ?>" class="action" data-aid="<?php echo( $ainfo[ 'aid' ] ); ?>"><?php echo( $ainfo[ 'aname' ] ); ?></a></td>
        <td><?php $qs = "SELECT contact_action.cid
                         FROM contact_action
                         WHERE aid = " . $ainfo[ 'aid' ];
            
            $nqr = execute_query( $qs, $mc );
            
            echo( mysql_num_rows( $nqr ) ); ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
