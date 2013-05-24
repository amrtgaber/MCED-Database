<?php
/* File: action_contact_sheet_form.php
 * Author: Amr Gaber
 * Created: 2013/1/11
 * Description: Handles adding a contact sheet for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a contact sheet." );
}

/* Must have privilege level of 1 or greater to add a contact sheet */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a contact sheet." );
}

/* Check for required fields */

/* if no id or csid is present first and last name must exist */
if( !isset( $_POST[ "csid" ] ) || $_POST[ "csid" ] == "" ) {
  if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
    alert_error( "First and Last name are required fields." );
  }
}

/* Issues */
if( !isset( $_POST[ "issues" ] ) || $_POST[ "issues" ] == "" ) {
  alert_error( "Issues is a required field." );
}

/* Rating */
if( !isset( $_POST[ "rating" ] ) || $_POST[ "rating" ] == "" ) {
  alert_error( "Rating is a required field." );
}

/* Organizer */
if( !isset( $_POST[ "oid" ] ) || $_POST[ "oid" ] == "" || $_POST[ "oid" ] == "undefined" ) {
  alert_error( "Organizer is a required field." );
}

/* Contact Type */
if( !isset( $_POST[ "contactType" ] ) || $_POST[ "contactType" ] == "" ) {
  alert_error( "Contact Type is a required field." );
}

/* Date */
if( !isset( $_POST[ "date" ] ) || $_POST[ "date" ] == "" ) {
  alert_error( "Date is a required field." );
}

/* connect to database */
$mc = connect_to_database();

/* Insert new contact sheet */
if ( $_POST[ "add" ] ) {
  /* new contact sheet */
  $id = mysql_real_escape_string( $_POST[ 'csid' ] );

  /* Insert new contact sheet */
  $qs = "INSERT INTO contact_sheet
        ( cid )
        VALUES ( " . $id . " )";

  $qr = execute_query( $qs, $mc );

  /* Get id of the contact sheet that was just added */
  $qs = "SELECT id
         FROM contact_sheet
         WHERE cid = " . $id . "
         ORDER BY id DESC
         LIMIT 1";
  $qr = execute_query( $qs, $mc );

  $cs_info = mysql_fetch_array( $qr );
  $csid = $cs_info[ 'id' ];
} else {
  /* modifying existing contact sheet */
  $csid = mysql_real_escape_string( $_POST[ 'csid' ] );
  
  $qs = "SELECT cid
         FROM contact_sheet
         WHERE id = " . $csid;
  
  $qr = execute_query( $qs, $mc );
  
  $cinfo = mysql_fetch_array( $qr );
  $id = $cinfo[ 'cid' ];
}

/* insert values */

/* workplace */
if( $_POST[ 'wid' ] ) {
  $wid = mysql_real_escape_string( $_POST[ 'wid' ] );
  
  $qs = "UPDATE contact_sheet
         SET wid = " . $wid . "
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* job */
if( $_POST[ 'job' ] ) {
  $job = mysql_real_escape_string( $_POST[ 'job' ] );
  
  $qs = "UPDATE contact_sheet
         SET job = '" . $job . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* shift */
if( $_POST[ 'shift' ] ) {
  $shift = mysql_real_escape_string( $_POST[ 'shift' ] );
  
  $qs = "UPDATE contact_sheet
         SET shift = '" . $shift . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* issues */
if( $_POST[ 'issues' ] ) {
  $issues = mysql_real_escape_string( $_POST[ 'issues' ] );
  
  $qs = "UPDATE contact_sheet
         SET issues = '" . $issues . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* reservations */
if( $_POST[ 'reservations' ] ) {
  $reservations = mysql_real_escape_string( $_POST[ 'reservations' ] );
  
  $qs = "UPDATE contact_sheet
         SET reservations = '" . $reservations . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* leaders identified */
if( $_POST[ 'leadersIdentified' ] ) {
  $leaders = mysql_real_escape_string( $_POST[ 'leadersIdentified' ] );
  
  $qs = "UPDATE contact_sheet
         SET leaders = '" . $leaders . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* comments */
if( $_POST[ 'comments' ] ) {
  $comments = mysql_real_escape_string( $_POST[ 'comments' ] );
  
  $qs = "UPDATE contact_sheet
         SET comments = '" . $comments . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* assignments */
if( $_POST[ 'assignments' ] ) {
  $assignments = mysql_real_escape_string( $_POST[ 'assignments' ] );
  
  $qs = "UPDATE contact_sheet
         SET assignments = '" . $assignments . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* rating */
if( $_POST[ 'rating' ] ) {
  $rating = mysql_real_escape_string( $_POST[ 'rating' ] );
  
  $qs = "UPDATE contact_sheet
         SET rating = " . $rating . "
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* story */
if( $_POST[ 'story' ] ) {
  $qs = "UPDATE contacts
         SET story = 1
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* video */
if( $_POST[ 'video' ] ) {
  $qs = "UPDATE contacts
         SET video = 1
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* survey */
if( $_POST[ 'survey' ] ) {
  $qs = "UPDATE contacts
         SET survey = 1
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* dues authorization card */
if( $_POST[ 'dac' ] ) {
  $qs = "UPDATE contacts
         SET dues_auth_card = 1
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* placard photo */
if( $_POST[ 'placard' ] ) {
  $qs = "UPDATE contacts
         SET placard_photo = 1
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* potential legal issues */
if( $_POST[ 'pliCheck' ] ) {
  $qs = "UPDATE contact_sheet
         SET has_legal_issues = 1
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
  
  if( $_POST[ "pliText" ] ) {
    $legal_issues = mysql_real_escape_string( $_POST[ "pliText" ] );
    
    $qs = "UPDATE contact_sheet
           SET legal_issues = '" . $legal_issues . "'
           WHERE id = " . $csid;

    $qr = execute_query( $qs, $mc );
  }
}

/* organizer */
if( $_POST[ 'oid' ] ) {
  $oid = mysql_real_escape_string( $_POST[ "oid" ] );
  
  /* insert into contact sheet */
  $qs = "UPDATE contact_sheet
         SET cs_oid = " . $oid . "
         WHERE id = " . $csid;
  
  $qr = execute_query( $qs, $mc );
}

/* contact type */
if( $_POST[ 'contactType' ] ) {
  if( $_POST[ "contactType" ] == "other" ) {
    $contact_type = mysql_real_escape_string( $_POST[ "ctoText" ] );
  } else {
    $contact_type = mysql_real_escape_string( $_POST[ "contactType" ] );
  }
    
  $qs = "UPDATE contact_sheet
         SET cs_contact_type = '" . $contact_type . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* date */
if( $_POST[ 'date' ] ) {
  $date = mysql_real_escape_string( $_POST[ "date" ] );

  $qs = "UPDATE contact_sheet
         SET cs_date = '" . $date . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* notes */
if( $_POST[ 'notes' ] ) {
  $notes = mysql_real_escape_string( $_POST[ 'notes' ] );
  
  $qs = "UPDATE contact_sheet
         SET cs_notes = '" . $notes . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* improvements */
if( $_POST[ 'improvements' ] ) {
  $improvements = mysql_real_escape_string( $_POST[ 'improvements' ] );
  
  $qs = "UPDATE contact_sheet
         SET improvements = '" . $improvements . "'
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* Return success */
if( $_POST[ 'add' ] ) { ?>
  <div class="alert alert-success" style="display: inline-block;">
    The contact sheet was successfully added.
  
    <div class="row-fluid">
      <a href="view_contact_sheet.php?csid=<?php echo( $csid ); ?>" class="btn btn-success mobile-margin span2">View</a>
      <a href="add_contact_sheet.php?csid=<?php echo( $id ); ?>" class="btn btn-primary mobile-margin span4">Add Another</a>
    </div>
  </div>
<?php } else { ?>
  <div class="alert alert-success" style="display: inline-block;">
    The contact sheet was successfully saved.
  
    <div class="row-fluid">
      <button type="button" class="btn btn-success mobile-margin span2" onclick="$( this ).parent().parent().hide(); $( '#save-button' ).removeAttr( 'disabled' );">OK</button>
    </div>
  </div>
<?php } ?>
