<?php
/* File: action_user_form.php
 * Author: Amr Gaber
 * Created: 2013/5/1
 * Description: Handles adding or updating users into KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !isset( $_SESSION[ 'username' ] ) ) {
  alert_error( "You must be logged in to add a user." );
}

/* Check for required fields */

/* Check that username exists */
if( !isset( $_POST[ 'username' ] ) || $_POST[ 'username' ] == "" ) {
  alert_error( "Username is a required field." );
}

/* username must be at least 4 characters in length */
if( strlen( $_POST[ 'username' ] ) < 4 ) {
  alert_error( "Username must be at least 4 characters in length." );
}

/* If new user, password must exist. */
if( $_POST[ 'add' ] ) {
  if( !isset( $_POST[ "newPassword" ] ) || $_POST[ "newPassword" ] == "" ) {
    alert_error( "New Password is a required field." );
  }
  
  if( !isset( $_POST[ "confirmPassword" ] ) || $_POST[ "confirmPassword" ] == "" ) {
    alert_error( "Confirm Password is a required field." );
  }
}

/* Passwords must match */
if( strcmp( $_POST[ 'newPassword' ], $_POST[ 'confirmPassword' ] ) != 0 ) {
  alert_error( "Passwords must match." );
}

/* Connect to database */
$mc = connect_to_database();

$username = mysql_real_escape_string( $_POST[ 'username' ] );

if( $_POST[ 'add' ] ) {
  /* new username must be unique */
  $qs = "SELECT username
         FROM users
         WHERE username = '" . $username . "'";
  
  $qr = execute_query( $qs, $mc );
  
  if( mysql_num_rows( $qr ) != 0 ) {
    alert_error( "This username is already in use. Please choose a new username." );
  }

  $qs = "INSERT INTO users
        ( username )
        VALUES
        ( '" . $username . "' )";

  $qr = execute_query( $qs, $mc );
  
  /* Get id of the user that was just added */
  $qs = "SELECT id
         FROM users
         WHERE username = '" . $username . "'";

  $qr = execute_query( $qs, $mc );

  $uinfo = mysql_fetch_array( $qr );
  $uid = $uinfo[ 'id' ];
} else {
  /* update existing user */
  $uid = mysql_real_escape_string( $_POST[ 'uid' ] );

  $qs = "UPDATE users
         SET username = '" . $username . "'
         WHERE id = " . $uid;

  $qr = execute_query( $qs, $mc );

  /* Get user information */
  $qs = "SELECT users.*
         FROM users
         WHERE users.id = " . $uid;

  $qr = execute_query( $qs, $mc );

  $uinfo = mysql_fetch_array( $qr );
}

if( $_POST[ 'newPassword' ] ) {
  $password = mysql_real_escape_string( $_POST[ 'newPassword' ] );
  $password = hash( "sha256", $password );
  
  $qs = "UPDATE users
         SET password = '" . $password . "'
         WHERE id = " . $uid;

  $qr = execute_query( $qs, $mc );
}

/* Return success */
if( $_POST[ 'add' ] ) { ?>
  <div class="alert alert-success" style="display: inline-block;">
    The user <?php echo( $username ); ?> has been successfully added and can login immediately.
  
    <div class="row-fluid">
      <a href="view_user.php?uid=<?php echo( $uid ); ?>" class="btn btn-success mobile-margin span2">View</a>
      <a href="add_user.php" class="btn btn-primary mobile-margin span4">Add Another</a>
    </div>
  </div>
<?php } else { ?>
  <div class="alert alert-success" style="display: inline-block;">
    The user <?php echo( $username ); ?> was successfully saved.
  
    <div class="row-fluid">
      <button type="button" class="btn btn-success mobile-margin span2" onclick="$( this ).parent().parent().hide(); $( '#save-button' ).removeAttr( 'disabled' );">OK</button>
    </div>
  </div>
<?php } ?>
