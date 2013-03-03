<?php
/* File: action_contact_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/3
 * Description: Handles adding a contact to an action for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add an action." );
}

/* connect to database */
$mc = connect_to_database();

/* Check that action id exists */
if( !isset( $_POST[ 'aid' ] ) || $_POST[ 'aid' ] == "" ) {
  alert_error( "Action Name is a required field." );
}

/* Check that contact id exists */
if( !isset( $_POST[ 'cid' ] ) || $_POST[ 'cid' ] == "" ) {
  alert_error( "Contact Name is a required field." );
}

/* Check that date exists */
if( !isset( $_POST[ 'date' ] ) || $_POST[ 'date' ] == "" ) {
  alert_error( "Date is a required field." );
}

/* assign post data */
$aid = mysql_real_escape_string( $_POST[ 'aid' ] );
$aname = mysql_real_escape_string( $_POST[ 'aname' ] );

$cid = mysql_real_escape_string( $_POST[ 'cid' ] );
$cname = mysql_real_escape_string( $_POST[ 'cname' ] );

$date = mysql_real_escape_string( $_POST[ "date" ] );

/* Must have privilege level of 1 or greater to add a contact to an action */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a contact to an action." );
}

/* Insert new contact to action */
$qs = "INSERT INTO contact_action
      ( cid, aid, date )
      VALUES ( " . $cid . ", " . $aid . ", '" . $date . "' )";

$qr = execute_query( $qs, $mc );

/* Return success */ ?>
<div class="alert alert-success">
  The contact <?php echo( $cname );?> was successfully added to the action <?php echo( $aname );?>.
  <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide();">OK</button>
</div>
