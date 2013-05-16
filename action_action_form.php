<?php
/* File: action_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/2
 * Description: Handles adding or updating an action for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to edit an action." );
}

/* connect to database */
$mc = connect_to_database();

/* Check that name exists */
if( !isset( $_POST[ 'aname' ] ) || $_POST[ 'aname' ] == "" ) {
  alert_error( "Action Name is a required field." );
}
  
$aname = mysql_real_escape_string( $_POST[ 'aname' ] );

if( $_POST[ 'add' ] ) {
  /* Insert new action */
  $qs = "INSERT INTO actions
        ( aname )
        VALUES ( '" . $aname . "' )";

  $qr = execute_query( $qs, $mc );

  /* Get id of the action that was just added */
  $qs = "SELECT aid
         FROM actions
         WHERE aname = '" . $aname . "'
         ORDER BY aid DESC
         LIMIT 1";
         
  $qr = execute_query( $qs, $mc );

  $ainfo = mysql_fetch_array( $qr );
  $aid = $ainfo[ 'aid' ];
} else {
  $aid = mysql_real_escape_string( $_POST[ 'aid' ] );

  /* Update existing action */
  $qs = "UPDATE actions
         SET aname = '" . $aname . "'
         WHERE aid = " . $aid;

  $qr = execute_query( $qs, $mc );
}

/* Add Contacts to this action */
if( $_POST[ "addContacts" ] ) {
  $contactIDs = explode( ",", $_POST[ "addContacts" ] );
  
  foreach( $contactIDs as $cid ) {
    $cid = mysql_real_escape_string( $cid );

    /* If cid exists don't insert */    
    $qs = "SELECT cid
           FROM contact_action
           WHERE cid = " . $cid . " AND aid = " . $aid;
    
    $aqr = execute_query( $qs, $mc );
    
    if( mysql_num_rows( $aqr ) == 0 ) {
      $qs = "INSERT INTO contact_action
          ( cid, aid, date )
          VALUES ( " . $cid . "," . $aid . ", curdate() )";

      execute_query( $qs, $mc );
    }
  }
  
  /* delete removed contacts */
  $qs = "SELECT cid
         FROM contact_action
         WHERE aid = " . $aid;
  
  $aqr = execute_query( $qs, $mc );
  
  while( $ainfo = mysql_fetch_array( $aqr ) ) {
    if( !in_array( $ainfo[ "cid" ], $contactIDs ) ) {
      $qs = "DELETE
             FROM contact_action
             WHERE cid = " . $ainfo[ "cid" ] . " AND aid = " . $aid;

      execute_query( $qs, $mc );
    }
  }
} else {
  $qs = "DELETE
         FROM contact_action
         WHERE aid = " . $aid;
  
  execute_query( $qs, $mc );
}

/* Return success */
if( $_POST[ 'add' ] ) { ?>
  <div class="alert alert-success">
    The action <?php echo( $aname );?> was successfully added to the database.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide(); $( 'form' ).each(function () { this.reset(); }); $( '.contact' ).each(function() { $( this ).remove(); }); $( '#add-contact-table' ).remove();">OK</button>
  </div>
<?php } else { ?>
  <div class="alert alert-success">
    The action <?php echo( $aname );?> was successfully modified.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide();">OK</button>
  </div>
<?php } ?>
