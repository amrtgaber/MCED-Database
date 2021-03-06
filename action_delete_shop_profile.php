<?php
/* File: action_delete_shop_profile.php
 * Author: Amr Gaber
 * Created: 2013/4/28
 * Description: Handles deleting a shop profile from the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to delete a shop profile." );
}

/* get wid */
$wid = mysql_real_escape_string( $_POST[ 'wid' ] );

/* Connect to database */
$mc = connect_to_database();

/* delete entry */
$qs = "DELETE workplaces.*,
              workers.*
       FROM workplaces
         LEFT JOIN workers ON workplaces.wid = workers.wid
       WHERE workplaces.wid = " . $wid;

$qr = execute_query( $qs, $mc ); ?>
