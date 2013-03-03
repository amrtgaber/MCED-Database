<?php
/* File: action_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/2
 * Description: Handles adding or updating an action for KC99 database.
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

/* Check that name exists */
if( !isset( $_POST[ 'aname' ] ) || $_POST[ 'aname' ] == "" ) {
  alert_error( "Action Name is a required fields." );
}
  
$aname = mysql_real_escape_string( $_POST[ 'aname' ] );

/* If id is present, update existing action. Otherwise insert new action. */
if( $_POST[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify an action */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to modify an action." );
  }

  $id = mysql_real_escape_string( $_POST[ 'id' ] );

  /* Update existing contact */
  $qs = "UPDATE actions
         SET aname = '" . $aname . "'
         WHERE aid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  /* Must have privilege level of 1 or greater to add an action */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add an action." );
  }

  /* Insert new contact */
  $qs = "INSERT INTO actions
        ( aname )
        VALUES ( '" . $aname . "' )";

  $qr = execute_query( $qs, $mc );
}

/* Return success */
if( $_POST[ 'id' ] ) { ?>
  <div class="alert alert-success">
    The action <?php echo( $aname );?> was successfully modified.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide();">OK</button>
  </div>
<?php } else { ?>
  <div class="alert alert-success">
    The action <?php echo( $aname );?> was successfully added to the database.
  </div>
<?php } ?>
