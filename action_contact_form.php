<?php
/* File: contact_update_action.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Handles adding or updating a contact for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a contact." );
}

/* connect to database */
$mc = connect_to_database();

/* Check that First Name and Last Name exist */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  alert_error( "First and Last name are required fields." );
}
  
$firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );

/* If id is present, update existing contact. Otherwise insert new contact. */
if( $_POST[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify a contact */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to add a contact." );
  }

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
                CONCAT( assigned_organizers.first_name, ' ', assigned_organizers.last_name ) AS assigned_organizer
         FROM contacts
         LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
         LEFT JOIN contact_email ON contacts.id = contact_email.cid
         LEFT JOIN workers       ON contacts.id = workers.cid
         LEFT JOIN workplaces    ON workers.wid = workplaces.wid
         LEFT JOIN students      ON contacts.id = students.cid
         LEFT JOIN ( 
                   SELECT contact_organizer.cid, contacts.first_name, contacts.last_name
                   FROM contacts, contact_organizer
                   WHERE contacts.id = contact_organizer.oid
                   ) assigned_organizers ON contacts.id = assigned_organizers.cid
         WHERE contacts.id = " . $id;

  $qr = execute_query( $qs, $mc );

  $contact_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a contact */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add a contact." );
  }

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

  $contact_info = mysql_fetch_array( $qr );
  $id = $contact_info[ 'id' ];
}

/* Contact type */
$contactType = mysql_real_escape_string( $_POST[ 'contactType' ] );

if( $contactType == "Worker" ) {
  $contactType = 1;
} else if ( $contactType == "Student" ) {
  $contactType = 2;
} else if ( $contactType == "Supporter" ) {
  $contactType = 3;
} else {
  $contactType = 0;
}

$qs = "UPDATE contacts
       SET contact_type = " . $contactType . "
       WHERE id = " . $id;

$qr = execute_query( $qs, $mc );

/* Street No */
if( $_POST[ 'address' ] ) {
  $streetno = mysql_real_escape_string( strtolower( $_POST[ 'address' ] ) );
  
  $qs = "UPDATE contacts
         SET street_no = '" . $streetno . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );

} else {
  if( !is_null( $contact_info[ 'street_no' ] ) ) {
    $qs = "UPDATE contacts
           SET street_no = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* City */
if( $_POST[ 'city' ] ) {
  $city = mysql_real_escape_string( strtolower( $_POST[ 'city' ] ) );
  
  $qs = "UPDATE contacts
         SET city = '" . $city . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $contact_info[ 'city' ] ) ) {
    $qs = "UPDATE contacts
           SET city = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* State */
if( $_POST[ 'state' ] ) {
  if( strlen( $_POST[ 'state' ] ) != 2 ) {
    alert_error( "State field is invalid." );
  } else {
    $state = mysql_real_escape_string( strtolower( $_POST[ 'state' ] ) );
    
    $qs = "UPDATE contacts
           SET state = '" . $state . "'
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
} else {
  if( !is_null( $contact_info[ 'state' ] ) ) {
    $qs = "UPDATE contacts
           SET state = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
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
} else {
  if( !is_null( $contact_info[ 'zipcode' ] ) ) {
    $qs = "UPDATE contacts
           SET zipcode = NULL
           WHERE id = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Apt No */
if( $_POST[ 'aptNo' ] ) {
  $aptno = mysql_real_escape_string( strtolower( $_POST[ 'aptNo' ] ) );
  
  $qs = "UPDATE contacts
         SET apt_no = '" . $aptno . "'
         WHERE id = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $contact_info[ 'apt_no' ] ) ) {
    $qs = "UPDATE contacts
           SET apt_no = NULL
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

    if( $hasPhoneInfo || !is_null( $contact_info[ 'phone' ] ) ) {
      $qs = "UPDATE contact_phone
             SET phone = " . $phone . "
             WHERE cid = " . $id;
    } else {
      $qs = "INSERT INTO contact_phone
            ( cid, phone )
            VALUES ( " . $id . ", " . $phone . " )";
    }

    $qr = execute_query( $qs, $mc );
    
    $hasPhoneInfo = true;
  }
} else {
  if( $contact_info[ 'phone' ] != 0 ) {
    $qs = "UPDATE contact_phone
           SET phone = 0
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
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
} else {
  if( $contact_info[ 'cell' ] != 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = 0
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Text Message Updates */
if( $_POST[ 'textUpdates' ] && $hasPhoneInfo ) {
  $qs = "UPDATE contact_phone
         SET text_updates = 1
         WHERE cid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( $contact_info[ 'text_updates' ] == 1 ) {
    $qs = "UPDATE contact_phone
           SET text_updates = 0
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

if( !$hasPhoneInfo ) {
  $qs = "DELETE
         FROM contact_phone
         WHERE cid = " . $id;

  $qr = execute_query( $qs, $mc );
}

/* Email */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    alert_error( "Email field is invalid." );
  } else {
    $email = mysql_real_escape_string( strtolower( $_POST[ 'email' ] ) );
    
    if( !is_null( $contact_info[ 'email' ] ) ) {
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
  }
} else {
  if( !is_null( $contact_info[ 'email' ] ) ) {
    $qs = "DELETE
           FROM contact_email
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Worker information */
$hasWorkerInfo      = false;

/* Employer */
if( $_POST[ 'employer' ] ) {
  $employer = mysql_real_escape_string( strtolower( $_POST[ 'employer' ] ) );

  if( $hasWorkerInfo || !is_null( $contact_info[ 'employer' ] ) ) {
    $qs = "UPDATE workers
           SET employer = '" . $employer . "'
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO workers
          ( cid, employer )
          VALUES ( " . $id . ", '" . $employer . "' )";
  }

  $qr = execute_query( $qs, $mc );
    
  $hasWorkerInfo = true;
} else {
  if( !is_null( $contact_info[ 'employer' ] ) ) {
    $qs = "UPDATE workers
           SET employer = NULL
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Wage below $10 / hour */
if( $_POST[ 'wageBelow10' ] ) {
  $wagebelow10 = 1;
  
  if( $hasWorkerInfo || !is_null( $contact_info[ 'wage_below_10' ] ) ) {
    $qs = "UPDATE workers
           SET wage_below_10 = " . $wagebelow10 . "
           WHERE cid = " . $id;
  } else {
    $qs = "INSERT INTO workers
          ( cid, wage_below_10 )
          VALUES ( " . $id . ", " . $wagebelow10 . " )";
  }

  $qr = execute_query( $qs, $mc );

  $hasWorkerInfo = true;
} else {
  if( $hasWorkerInfo || !is_null( $contact_info[ 'wage_below_10' ] ) ) {
    $qs = "UPDATE workers
           SET wage_below_10 = NULL
           WHERE cid = " . $id;
    $qr = execute_query( $qs, $mc );
  }
}

/* Wage */
if( $_POST[ 'dollars' ] ) {
  if( !ctype_digit( $_POST[ 'dollars' ] ) ) {
    alert_error( "Dollars field is invalid." );
  } else {
    if( $_POST[ 'cents' ] ) {
      if( !ctype_digit( $_POST[ 'cents' ] ) || strlen( $_POST[ 'cents' ] ) != 2 ) {
        alert_error( "Cents field is invalid." );
      } else {
        /* dollars and cents */
        $wage = mysql_real_escape_string( $_POST[ 'dollars' ] )
                . "."
                . mysql_real_escape_string( $_POST[ 'cents' ] );
      }
    } else {
      /* dollars only */
      $wage = mysql_real_escape_string( $_POST[ 'dollars' ] );
    }
  }

  if( $hasWorkerInfo || !is_null( $contact_info[ 'wage' ] ) ) {
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
  if( !is_null( $contact_info[ 'wage' ] ) ) {
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

/* Student information */
$hasStudentInfo = false;

/* School */
if( $_POST[ 'school' ] ) {
  $school = mysql_real_escape_string( strtolower( $_POST[ 'school' ] ) );

  if( $hasStudentInfo  || !is_null( $contact_info[ 'school' ] ) ) {
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
  if( !is_null( $contact_info[ 'school' ] ) ) {
    $qs = "DELETE
           FROM students
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* School Year */
if( $_POST[ 'syear' ] ) {
  if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
    alert_error( "School Year field is invalid." );
  } else {
    $syear = mysql_real_escape_string( $_POST[ 'syear' ] );
  
    if( $hasStudentInfo || !is_null( $contact_info[ 'syear' ] ) ) {
      $qs = "UPDATE students
             SET syear = " . $syear . "
             WHERE cid = " . $id;
    
      $qr = execute_query( $qs, $mc );
    }
  }
} else {
  if( !is_null( $contact_info[ 'syear' ] ) ) {
    $qs = "UPDATE students
           SET syear = NULL
           WHERE cid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Return success */
if( $_POST[ 'id' ] ) { ?>
  <div class="alert alert-success">
    The contact <?php echo( $firstname . ' ' . $lastname );?> was successfully modified.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide();">OK</button>
  </div>
<?php } else { ?>
  <div class="alert alert-success">
    The contact <?php echo( $firstname . ' ' . $lastname );?> was successfully added to the database.
  </div>
<?php } ?>
