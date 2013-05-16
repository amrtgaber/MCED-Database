<?php
/* File: contact_update_action.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Handles adding or updating a contact for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a contact." );
}

/* connect to database */
$mc = connect_to_database();

/* Check for required fields */

/* Check that First Name and Last Name exist */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  alert_error( "First and Last name are required fields." );
}

/* Check that date exists */
if( !isset( $_POST[ "date" ] ) || $_POST[ "date" ] == "" ) {
  alert_error( "Date is a required field." );
}

/* Ensure fields have been entered correctly */

/* Check that state is two characters */
if( $_POST[ 'state' ] ) {
  if( strlen( $_POST[ 'state' ] ) != 2 ) {
    alert_error( "State field is must be two letter abbreviation." );
  }
}

/* Check that zipcode is 5 digits */
if( $_POST[ 'zipcode' ] ) {
  if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
    alert_error( "Zipcode field must be exactly 5 digits." );
  }
}

/* Check that phone numbers is 10 digits */
if( $_POST[ 'phone' ] ) {
  if( !ctype_digit( $_POST[ 'phone' ] ) || strlen( $_POST[ 'phone' ] ) != 10 ) {
    alert_error( "Phone number must be exactly 10 digits." );
  }
}

/* Check that email is valid string */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    alert_error( "Email field is invalid." );
  }
}

/* Check that wage is contains only digits and is correct length */
if( $_POST[ 'dollars' ] ) {
  if( !ctype_digit( $_POST[ 'dollars' ] ) ) {
    alert_error( "Dollars field can only contain digits." );
  }
}

if( $_POST[ 'cents' ] ) {
  if( !ctype_digit( $_POST[ 'cents' ] ) || strlen( $_POST[ 'cents' ] ) != 2 ) {
    alert_error( "Cents field must be exactly 2 digits." );
  }
}

/* Check syear contains only 2 digits */
if( $_POST[ 'syear' ] ) {
  if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
    alert_error( "School Year must be exactly 2 digits." );
  }
}

$firstname = mysql_real_escape_string( $_POST[ 'firstName' ] );
$lastname  = mysql_real_escape_string( $_POST[ 'lastName' ] );

if( $_POST[ 'add' ] ) {
  /* Insert new contact */
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

  $cinfo = mysql_fetch_array( $qr );
  $id = $cinfo[ 'id' ];
} else {
  $id = mysql_real_escape_string( $_POST[ 'id' ] );

  /* Update existing contact */
  $qs = "UPDATE contacts
         SET first_name = '" . $firstname . "',
         last_name = '" . $lastname . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );

  /* Get contact information */
  $qs = "SELECT contacts.*,
                contact_phone.*,
                contact_email.*,
                workers.*,
                students.*,
                contact_action.*
         FROM contacts
           LEFT JOIN contact_phone  ON contacts.id = contact_phone.cid
           LEFT JOIN contact_email  ON contacts.id = contact_email.cid
           LEFT JOIN workers        ON contacts.id = workers.cid
           LEFT JOIN workplaces     ON workers.wid = workplaces.wid
           LEFT JOIN students       ON contacts.id = students.cid
           LEFT JOIN contact_action ON contacts.id = contact_action.cid
         WHERE contacts.id = " . $id;

  $qr = execute_query( $qs, $mc );

  $cinfo = mysql_fetch_array( $qr );
}

/* Contact type */
$contactType = mysql_real_escape_string( $_POST[ 'contactType' ] );

$qs = "UPDATE contacts
       SET contact_type = " . $contactType . "
       WHERE id = " . $id;

$qr = execute_query( $qs, $mc );

/* Date */
$date = mysql_real_escape_string( $_POST[ "date" ] );

$qs = "UPDATE contacts
       SET cdate = '" . $date . "'
       WHERE id = " . $id;

$qr = execute_query( $qs, $mc );

/* Notes */
$notes = mysql_real_escape_string( $_POST[ 'notes' ] );
  
$qs = "UPDATE contacts
       SET notes = '" . $notes . "'
       WHERE id = " . $id;

$qr = execute_query( $qs, $mc );

/* Street No */
if( $_POST[ 'address' ] ) {
  $streetno = mysql_real_escape_string( $_POST[ 'address' ] );
  
  $qs = "UPDATE contacts
         SET street_no = '" . $streetno . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );

} else {
  if( !is_null( $cinfo[ 'street_no' ] ) ) {
    $qs = "UPDATE contacts
           SET street_no = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* City */
if( $_POST[ 'city' ] ) {
  $city = mysql_real_escape_string( $_POST[ 'city' ] );
  
  $qs = "UPDATE contacts
         SET city = '" . $city . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $cinfo[ 'city' ] ) ) {
    $qs = "UPDATE contacts
           SET city = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* State */
if( $_POST[ 'state' ] ) {
  $state = mysql_real_escape_string( strtoupper( $_POST[ 'state' ] ) );
  
  $qs = "UPDATE contacts
         SET state = '" . $state . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $cinfo[ 'state' ] ) ) {
    $qs = "UPDATE contacts
           SET state = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Zipcode */
if( $_POST[ 'zipcode' ] ) {
  $zipcode = mysql_real_escape_string( $_POST[ 'zipcode' ] );
  
  $qs = "UPDATE contacts
         SET zipcode = " . $zipcode . "
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $cinfo[ 'zipcode' ] ) ) {
    $qs = "UPDATE contacts
           SET zipcode = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Apt No */
if( $_POST[ 'aptNo' ] ) {
  $aptno = mysql_real_escape_string( $_POST[ 'aptNo' ] );
  
  $qs = "UPDATE contacts
         SET apt_no = '" . $aptno . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $cinfo[ 'apt_no' ] ) ) {
    $qs = "UPDATE contacts
           SET apt_no = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Phone Number */
if( $_POST[ 'phone' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone' ] );

  if( $cinfo[ 'phone' ] != 0 ) {
    $qs = "UPDATE contact_phone
           SET phone = " . $phone . "
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO contact_phone
          ( cid, phone )
          VALUES ( " . $id . ", " . $phone . " )";
  }

  $qr = execute_query( $qs, $mc );
} else {
  if( $cinfo[ 'phone' ] != 0 ) {
    $qs = "DELETE
           FROM contact_phone
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Email */
if( $_POST[ 'email' ] ) {
  $email = mysql_real_escape_string( $_POST[ 'email' ] );
  
  if( !is_null( $cinfo[ 'email' ] ) ) {
    $qs = "UPDATE contact_email
           SET email = '" . $email . "'
           WHERE cid = " . $id;
    
    $qr = execute_query( $qs, $mc );
  } else {
    $qs = "INSERT INTO contact_email
          ( cid, email )
          VALUES ( " . $id . ", '" . $email . "' )";

    $qr = execute_query( $qs, $mc );
  }
} else {
  if( !is_null( $cinfo[ 'email' ] ) ) {
    $qs = "DELETE
           FROM contact_email
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Worker information */
$hasWorkerInfo = false;

/* workplace */
if( $_POST[ 'wid' ] ) {
  $wid = mysql_real_escape_string( $_POST[ 'wid' ] );

  if( $hasWorkerInfo || !is_null( $cinfo[ 'wid' ] ) ) {
    $qs = "UPDATE workers
           SET wid = " . $wid . "
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO workers
          ( cid, wid )
          VALUES ( " . $id . ", " . $wid . " )";
  }

  $qr = execute_query( $qs, $mc );
    
  $hasWorkerInfo = true;
} else {
  if( $cinfo[ 'wid' ] != 0 ) {
    $qs = "UPDATE workers
           SET wid = 0
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Wage */
if( $_POST[ 'dollars' ] ) {
  if( $_POST[ 'cents' ] ) {
    /* dollars and cents */
    $wage = mysql_real_escape_string( $_POST[ 'dollars' ] )
            . "."
            . mysql_real_escape_string( $_POST[ 'cents' ] );
  } else {
    /* dollars only */
    $wage = mysql_real_escape_string( $_POST[ 'dollars' ] );
  }

  if( $hasWorkerInfo || !is_null( $cinfo[ 'wage' ] ) ) {
    $qs = "UPDATE workers
           SET wage = " . $wage . "
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO workers
          ( cid, wage )
          VALUES ( " . $id . ", " . $wage . " )";
  }

  $qr = execute_query( $qs, $mc );
    
  $hasWorkerInfo = true;
} else {
  if( !is_null( $cinfo[ 'wage' ] ) ) {
    $qs = "UPDATE workers
           SET wage = NULL
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

if( !$hasWorkerInfo ) {
  $qs = "DELETE
         FROM workers
         WHERE cid = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* Action */
if( $_POST[ 'actions' ] ) {
  $actions = explode( ",", $_POST[ "actions" ] );
  
  foreach( $actions as $aid ) {
    $aid = mysql_real_escape_string( $aid );

    /* If aid exists don't insert */    
    $qs = "SELECT aid
           FROM contact_action
           WHERE aid = " . $aid . " AND cid = " . $id;
    
    $aqr = execute_query( $qs, $mc );
    
    if( mysql_num_rows( $aqr ) == 0 ) {
      $qs = "INSERT INTO contact_action
          ( cid, aid, date )
          VALUES ( " . $id . ", " . $aid . ", '" . $date . "' )";

      execute_query( $qs, $mc );
    }
  }
  
  /* delete removed workers */
  $qs = "SELECT aid
         FROM contact_action
         WHERE cid = " . $id;
  
  $aqr = execute_query( $qs, $mc );
  
  while( $ainfo = mysql_fetch_array( $aqr ) ) {
    if( !in_array( $ainfo[ "aid" ], $actions ) ) {
      $qs = "DELETE
             FROM contact_action
             WHERE aid = " . $ainfo[ "aid" ] . " AND cid = " . $id;

      execute_query( $qs, $mc );
    }
  }
} else {
  $qs = "DELETE
         FROM contact_action
         WHERE cid = " . $id;
  
  execute_query( $qs, $mc );
}

/* Student information */
$hasStudentInfo = false;

/* School */
if( $_POST[ 'school' ] ) {
  $school = mysql_real_escape_string( $_POST[ 'school' ] );

  if( $hasStudentInfo  || !is_null( $cinfo[ 'school' ] ) ) {
    $qs = "UPDATE students
           SET school = '" . $school . "'
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO students
          ( cid, school )
          VALUES ( " . $id . ", '" . $school . "' )";
  }

  $qr = execute_query( $qs, $mc );
  
  $hasStudentInfo = true;
} else {
  if( !is_null( $cinfo[ 'school' ] ) ) {
    $qs = "DELETE
           FROM students
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* School Year */
if( $_POST[ 'syear' ] ) {
  $syear = mysql_real_escape_string( $_POST[ 'syear' ] );

  if( $hasStudentInfo || !is_null( $cinfo[ 'syear' ] ) ) {
    $qs = "UPDATE students
           SET syear = " . $syear . "
           WHERE cid = " . $id;
  
    $qr = execute_query( $qs, $mc );
  }
} else {
  if( !is_null( $cinfo[ 'syear' ] ) ) {
    $qs = "UPDATE students
           SET syear = NULL
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Return success */
if( $_POST[ 'add' ] ) { ?>
  <div>
    <div class="alert alert-success">
      The contact <?php echo( $firstname . ' ' . $lastname ); ?> was successfully added to the database.
      <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().parent().hide();  $( '#contact-form' ).each(function () { this.reset(); }); $( '.action-select' ).each(function() { if( $( this ).attr( 'data-last' ) != 'true' ) { $( this ).parent().parent().remove(); } });">OK</button>
    </div>
    
    <div class="alert alert-info">
      Add contact sheet <a href="add_contact_sheet.php?csid=<?php echo( $id ); ?>" class="btn btn-info">+</a>
    </div>
  </div>
<?php } else { ?>
  <div class="alert alert-success">
    The contact <?php echo( $firstname . ' ' . $lastname ); ?> was successfully saved.
    <button type="button" class="btn btn-small btn-success" onclick="$( this ).parent().hide();">OK</button>
  </div>
<?php } ?>
