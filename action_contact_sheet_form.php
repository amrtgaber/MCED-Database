<?php
/* File: action_contact_sheet_form.php
 * Author: Amr Gaber
 * Created: 2013/1/11
 * Description: Handles adding a contact sheet for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a contact sheet." );
}

/* Must have privilege level of 1 or greater to add a contact sheet */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a contact sheet." );
}

/* Check for required fields */

/* if no id is present first and last name must exist */
if( !isset( $_POST[ "id" ] ) || $_POST[ "id" ] == "" ) {
  if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
    alert_error( "First and Last name are required fields." );
  }
}

/* Issues */
if( !isset( $_POST[ "issues" ] ) || $_POST[ "issues" ] == "" ) {
  alert_Error( "Issues is a required field." );
}

/* Rating */
if( !isset( $_POST[ "rating" ] ) || $_POST[ "rating" ] == "" ) {
  alert_Error( "Rating is a required field." );
}

/* Organizer */
if( !isset( $_POST[ "organizer" ] ) || $_POST[ "organizer" ] == "" ) {
  alert_Error( "Organizer is a required field." );
}

/* Contact Type */
if( !isset( $_POST[ "contactType" ] ) || $_POST[ "contactType" ] == "" ) {
  alert_Error( "Contact Type is a required field." );
}

/* Date */
if( !isset( $_POST[ "date" ] ) || $_POST[ "date" ] == "" ) {
  alert_Error( "Date is a required field." );
}

/* connect to database */
$mc = connect_to_database();

/* Insert new contact sheet */

/* If id is not present, need to insert new contact. */
if( !isset( $_POST[ "id" ] ) || $_POST[ "id" ] == "" ) {
  /* Insert new contact */
  $firstname = mysql_real_escape_string( $_POST[ 'firstName' ] );
  $lastname  = mysql_real_escape_string( $_POST[ 'lastName' ] );
  
  $qs = "INSERT INTO contacts
        ( first_name, last_name )
        VALUES ( '" . $firstname . "', '" . $lastname . "' )";

  $qr = execute_query( $qs, $mc );

  /* Get id of the contact that was just added */
  $qs = "SELECT id
         FROM contacts
         WHERE first_name = '" . $firstname . "' AND last_name = '" . $lastname. "'
         ORDER BY id DESC
         LIMIT 1";
  $qr = execute_query( $qs, $mc );

  $contact_info = mysql_fetch_array( $qr );
  $id = $contact_info[ 'id' ];
  
  /* insert address, city, zip and phone numbers */
  
  /* address */
  if( $_POST[ 'address' ] ) {
    $streetno = mysql_real_escape_string( strtolower( $_POST[ 'address' ] ) );
    
    $qs = "UPDATE contacts
           SET street_no = '" . $streetno . "'
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
  
  /* City */
  if( $_POST[ 'city' ] ) {
    $city = mysql_real_escape_string( strtolower( $_POST[ 'city' ] ) );
    
    $qs = "UPDATE contacts
           SET city = '" . $city . "'
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
  
  /* Zipcode */
  if( $_POST[ 'zipcode' ] ) {
    if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
      alert_error( "Zipcode field is invalid." );
    } else {
      $zipcode = mysql_real_escape_string( $_POST[ 'zipcode' ] );
      
      $qs = "UPDATE contacts
             SET zipcode = " . $zipcode . "
             WHERE id = " . $id;

      $qr = execute_query( $qs, $mc );
    }
  }
  
  /* Phone Information */
  $hasPhoneInfo = false;

  /* Phone Number */
  if( $_POST[ 'phone' ] ) {
    if( !ctype_digit( $_POST[ 'phone' ] ) || ( strlen( $_POST[ 'phone' ] ) != 7 && strlen( $_POST[ 'phone' ] ) != 10 ) ) {
      alert_error( "Phone field is invalid." );
    } else {
      $phone = mysql_real_escape_string( $_POST[ 'phone' ] );

      $qs = "INSERT INTO contact_phone
            ( cid, phone )
            VALUES ( " . $id . ", " . $phone . " )";

      $qr = execute_query( $qs, $mc );
      
      $hasPhoneInfo = true;
    }
  }

  /* Cell Phone Number */
  if( $_POST[ 'cell' ] ) {
    if( !ctype_digit( $_POST[ 'cell' ] ) || ( strlen( $_POST[ 'cell' ] ) != 7 && strlen( $_POST[ 'cell' ] ) != 10 ) ) {
      alert_error( "Cell Phone field is invalid." );
    } else {
      $cell = mysql_real_escape_string( $_POST[ 'cell' ] );

      if( $hasPhoneInfo || !is_null( $contact_info[ 'cell' ] ) ) {
        $qs = "UPDATE contact_phone
               SET cell = " . $cell . "
               WHERE cid = " . $id;
      } else {
        $qs = "INSERT INTO contact_phone
              ( cid, cell )
              VALUES ( " . $id . ", " . $cell . " )";
      }

      $qr = execute_query( $qs, $mc );
        
      $hasPhoneInfo = true;
    }
  }
} else {
  /* id is present */
  $id = mysql_real_escape_string( $_POST[ 'id' ] );
}

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

/* insert values */

/* workplace */
if( $_POST[ 'workplace' ] ) {
  $workplace = mysql_real_escape_string( $_POST[ 'workplace' ] );
  
  $qs = "UPDATE contact_sheet
         SET workplace = '" . $workplace . "'
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
  $qs = "UPDATE contact_sheet
         SET story = 1
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* video */
if( $_POST[ 'video' ] ) {
  $qs = "UPDATE contact_sheet
         SET video = 1
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* survey */
if( $_POST[ 'survey' ] ) {
  $qs = "UPDATE contact_sheet
         SET survey = 1
         WHERE id = " . $csid;

  $qr = execute_query( $qs, $mc );
}

/* dues authorization card */
if( $_POST[ 'dac' ] ) {
  $qs = "UPDATE contact_sheet
         SET dues_card = 1
         WHERE id = " . $csid;

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
if( $_POST[ 'organizer' ] ) {
  $organizer = mysql_real_escape_string( $_POST[ "organizer" ] );
  
  /* get oid */
  $qs = "SELECT id
         FROM users
         WHERE username = '" . $organizer . "'";
         
  $qr = execute_query( $qs, $mc );

  $organizer_info = mysql_fetch_array( $qr );
  $oid = $organizer_info[ "id" ];
  
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

/* Return success */ ?>
<div class="alert alert-success">
  The contact sheet was successfully added.
  <button type="button" class="btn btn-small btn-success" onclick="$( this ).parent().hide();">OK</button>
</div>

