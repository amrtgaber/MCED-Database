<?php
/* File: load_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/2
 * Description: Returns the action add/modify form for KC99 database.
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

/* If id is present, populate form. */
if( $_GET[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify an action */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to modify an action." );
  }

  $id = mysql_real_escape_string( $_GET[ 'id' ] );

  /* Get contact information */
  $qs = "SELECT *
         FROM actions
         WHERE aid = " . $id;
  
  $qr = execute_query( $qs, $mc );

  $contact_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a contact */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add an action." );
  }

  $contact_info = Array();
}

?>

<div class="well"> 
  <div class="row-fluid">
    <div class="span2">Action Name</div>
    <div class="span10">
      <input type="text" name="aname" class="span12" id="aname"
             value="<?php echo( $action_info[ 'aname' ] ); ?>"
             placeholder="Type action name here"
             required>
  </div>
</div>
