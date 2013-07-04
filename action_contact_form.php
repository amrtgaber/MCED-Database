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

/* Check that phone 1 numbers is 10 digits */
if( $_POST[ 'phone1' ] ) {
  if( !ctype_digit( $_POST[ 'phone1' ] ) || strlen( $_POST[ 'phone1' ] ) != 10 ) {
    alert_error( "Phone number must be exactly 10 digits." );
  }
}

/* Check that phone 2 numbers is 10 digits */
if( $_POST[ 'phone2' ] ) {
  if( !ctype_digit( $_POST[ 'phone2' ] ) || strlen( $_POST[ 'phone2' ] ) != 10 ) {
    alert_error( "Phone number must be exactly 10 digits." );
  }
}

/* Check that phone 3 numbers is 10 digits */
if( $_POST[ 'phone3' ] ) {
  if( !ctype_digit( $_POST[ 'phone3' ] ) || strlen( $_POST[ 'phone3' ] ) != 10 ) {
    alert_error( "Phone number must be exactly 10 digits." );
  }
}

/* Check that phone 4 numbers is 10 digits */
if( $_POST[ 'phone4' ] ) {
  if( !ctype_digit( $_POST[ 'phone4' ] ) || strlen( $_POST[ 'phone4' ] ) != 10 ) {
    alert_error( "Phone number must be exactly 10 digits." );
  }
}

/* Check that email is valid string */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    alert_error( "Email field is invalid." );
  }
}

/* Check that wages are numbers */
if( $_POST[ 'wages' ] ) {
  $wages = explode( ",", $_POST[ "wages" ] );
  
  foreach( $wages as $wage ) {
    if( $wage != "" && !is_numeric( $wage ) ) {
      alert_error( "Wage must be a number." );
    }
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

/* organizer */
if( $_POST[ 'woid' ] ) {
  $oid = mysql_real_escape_string( $_POST[ "woid" ] );
  
  /* insert into contacts */
  $qs = "UPDATE contacts
         SET wit_oid = " . $oid . "
         WHERE id = " . $id;
  
  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $cinfo[ 'wit_oid' ] ) ) {
    $qs = "UPDATE contacts
           SET wit_oid = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
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

$phones = Array();

/* Phone 1 Number */
if( $_POST[ 'phone1' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone1' ] );
  $phones[] = $phone;
  
  if( $_POST[ 'cell1' ] ) {
    $cell = 1;
  } else {
    $cell = 0;
  }
  
  if( $_POST[ 'text1' ] && $cell == 1 ) {
    $text = 1;
  } else {
    $text = 0;
  }
  
  $qs = "SELECT contact_phone.*
         FROM contact_phone
         WHERE cid = " . $id . " AND phone = " . $phone;
  
  $pqr = execute_query( $qs, $mc );
  $pinfo = mysql_fetch_array( $pqr );

  if( mysql_num_rows( $pqr ) > 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = " . $cell . ",
               main = 1,
               text_updates = " . $text . "
           WHERE cid = " . $id . " AND phone = " . $phone;
  } else {
    $qs = "INSERT INTO contact_phone
          ( cid, phone, cell, main, text_updates )
          VALUES
          ( " . $id . ", " . $phone . ", " . $cell . ", 1, " . $text . " )";
  }

  execute_query( $qs, $mc );
}

/* Phone 2 Number */
if( $_POST[ 'phone2' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone2' ] );
  $phones[] = $phone;
  
  if( $_POST[ 'cell2' ] ) {
    $cell = 1;
  } else {
    $cell = 0;
  }
  
  if( $_POST[ 'text2' ] && $cell == 1 ) {
    $text = 1;
  } else {
    $text = 0;
  }
  
  $qs = "SELECT contact_phone.*
         FROM contact_phone
         WHERE cid = " . $id . " AND phone = " . $phone;
  
  $pqr = execute_query( $qs, $mc );
  $pinfo = mysql_fetch_array( $pqr );

  if( mysql_num_rows( $pqr ) > 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = " . $cell . ",
               main = 0,
               text_updates = " . $text . "
           WHERE cid = " . $id . " AND phone = " . $phone;
  } else {
    $qs = "INSERT INTO contact_phone
          ( cid, phone, cell, main, text_updates )
          VALUES
          ( " . $id . ", " . $phone . ", " . $cell . ", 0, " . $text . " )";
  }

  execute_query( $qs, $mc );
}

/* Phone 3 Number */
if( $_POST[ 'phone3' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone3' ] );
  $phones[] = $phone;
  
  if( $_POST[ 'cell3' ] ) {
    $cell = 1;
  } else {
    $cell = 0;
  }
  
  if( $_POST[ 'text3' ] && $cell == 1 ) {
    $text = 1;
  } else {
    $text = 0;
  }
  
  $qs = "SELECT contact_phone.*
         FROM contact_phone
         WHERE cid = " . $id . " AND phone = " . $phone;
  
  $pqr = execute_query( $qs, $mc );
  $pinfo = mysql_fetch_array( $pqr );

  if( mysql_num_rows( $pqr ) > 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = " . $cell . ",
               main = 0,
               text_updates = " . $text . "
           WHERE cid = " . $id . " AND phone = " . $phone;
  } else {
    $qs = "INSERT INTO contact_phone
          ( cid, phone, cell, main, text_updates )
          VALUES
          ( " . $id . ", " . $phone . ", " . $cell . ", 0, " . $text . " )";
  }

  execute_query( $qs, $mc );
}

/* Phone 4 Number */
if( $_POST[ 'phone4' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone4' ] );
  $phones[] = $phone;
  
  if( $_POST[ 'cell4' ] ) {
    $cell = 1;
  } else {
    $cell = 0;
  }
  
  if( $_POST[ 'text4' ] && $cell == 1 ) {
    $text = 1;
  } else {
    $text = 0;
  }
  
  $qs = "SELECT contact_phone.*
         FROM contact_phone
         WHERE cid = " . $id . " AND phone = " . $phone;
  
  $pqr = execute_query( $qs, $mc );
  $pinfo = mysql_fetch_array( $pqr );

  if( mysql_num_rows( $pqr ) > 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = " . $cell . ",
               main = 0,
               text_updates = " . $text . "
           WHERE cid = " . $id . " AND phone = " . $phone;
  } else {
    $qs = "INSERT INTO contact_phone
          ( cid, phone, cell, main, text_updates )
          VALUES
          ( " . $id . ", " . $phone . ", " . $cell . ", 0, " . $text . " )";
  }

  execute_query( $qs, $mc );
}

/* delete removed phone numbers */
$qs = "SELECT contact_phone.phone
       FROM contact_phone
       WHERE cid = " . $id;

$pqr = execute_query( $qs, $mc );

while( $pinfo = mysql_fetch_array( $pqr ) ) {
  if( !in_array( $pinfo[ "phone" ], $phones ) ) {
    $qs = "DELETE
           FROM contact_phone
           WHERE phone = " . $pinfo[ "phone" ] . " AND cid = " . $id;

    execute_query( $qs, $mc );
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

/* Workplace information */
if( $_POST[ 'workplaces' ] ) {
  $workplaces = explode( ",", $_POST[ "workplaces" ] );
  $wages = explode( ",", $_POST[ "wages" ] );

  for( $idx = 0, $sz = count( $workplaces ); $idx < $sz; $idx++ ) {
    $wid = mysql_real_escape_string( $workplaces[ $idx ] );
    $wage = mysql_real_escape_string( $wages[ $idx ]  );
    
    if( $wage == "" ) {
      $wage = "NULL";
    }

    /* If wid exists don't insert just update wage */
    $qs = "SELECT wid
           FROM workers
           WHERE wid = " . $wid . " AND cid = " . $id;
    
    $wqr = execute_query( $qs, $mc );
    
    if( mysql_num_rows( $wqr ) == 0 ) {
      $qs = "INSERT INTO workers
          ( cid, wid, wage )
          VALUES
          ( " . $id . ", " . $wid . ", " . $wage . " )";

      execute_query( $qs, $mc );
    } else {
      $qs = "UPDATE workers
             SET wage = " . $wage . "
             WHERE wid = " . $wid . " AND cid = " . $id;
      
      execute_query( $qs, $mc );
    }
  }
  
  /* delete removed workplaces */
  $qs = "SELECT wid
         FROM workers
         WHERE cid = " . $id;
  
  $wqr = execute_query( $qs, $mc );
  
  while( $winfo = mysql_fetch_array( $wqr ) ) {
    if( !in_array( $winfo[ "wid" ], $workplaces ) ) {
      $qs = "DELETE
             FROM workers
             WHERE wid = " . $winfo[ "wid" ] . " AND cid = " . $id;

      execute_query( $qs, $mc );
    }
  }
} else {
  $qs = "DELETE
         FROM workers
         WHERE cid = " . $id;
  
  execute_query( $qs, $mc );
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
  
  /* delete removed actions */
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
  <div class="alert alert-success" style="display: inline-block;">
    The contact <?php echo( $firstname . ' ' . $lastname ); ?> was successfully added to the database.
  
    <div class="row-fluid">
      <a href="view_contact.php?id=<?php echo( $id ); ?>" class="btn btn-success mobile-margin span2">View</a>
      <a href="add_contact.php" class="btn btn-primary mobile-margin span4">Add Another</a>
      <a href="add_contact_sheet.php?csid=<?php echo( $id ); ?>" class="btn btn-info mobile-margin span5">Add Contact Sheet</a>
    </div>
  </div>
<?php } else { ?>
  <div class="alert alert-success" style="display: inline-block;">
    The contact <?php echo( $firstname . ' ' . $lastname ); ?> was successfully saved.
  
    <div class="row-fluid">
      <button type="button" class="btn btn-success mobile-margin span2" onclick="$( this ).parent().parent().hide(); $( '#save-button' ).removeAttr( 'disabled' );">OK</button>
    </div>
  </div>
<?php } ?>
