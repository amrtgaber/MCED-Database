<?php
/* File: contact_update_action.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Handles adding or updating a contact for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Check that First Name and Last Name exist */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  echo( "Invalid Name" );
  exit;
}
  
$firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );

/* If id is present, update existing contact. Otherwise insert new contact. */
if( $_POST[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify a contact */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    echo( "Permission Denied" );
    exit;
  }

  $id = mysql_real_escape_string( $_POST[ 'id' ] );

  /* Update existing contact */
  $qs = "UPDATE contacts
         SET first_name = '" . $firstname . "',
         last_name = '" . $lastname . "'
         WHERE id = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }

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
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }

  $contact_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a contact */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    echo( "Permission Denied" );
    exit;
  }

  /* Insert new contact */
  $qs = "INSERT INTO contacts
        ( first_name, last_name )
        VALUES ( '" . $firstname . "', '" . $lastname . "' )";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }

  /* Get id of the contact that was just added */
  $qs = "SELECT id
         FROM contacts
         WHERE first_name = '" . $firstname . "' AND last_name = '" . $lastname. "'
         ORDER BY id DESC
         LIMIT 1";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }

  $contact_info = mysql_fetch_array( $qr );
  $id = $contact_info[ 'id' ];
}

/* Contact type */
$contactType = mysql_real_escape_string( strtolower( $_POST[ 'contactType' ] ) );
$qs = "UPDATE contacts
       SET contact_type = '" . $contactType . "'
       WHERE id = " . $id;

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error " . mysql_error() );
  exit;
}

/* Street No */
if( $_POST[ 'address' ] ) {
  $streetno = mysql_real_escape_string( strtolower( $_POST[ 'address' ] ) );
  
  $qs = "UPDATE contacts
         SET street_no = '" . $streetno . "'
         WHERE id = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
} else {
  if( !is_null( $contact_info[ 'street_no' ] ) ) {
    $qs = "UPDATE contacts
           SET street_no = NULL
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* City */
if( $_POST[ 'city' ] ) {
  $city = mysql_real_escape_string( strtolower( $_POST[ 'city' ] ) );
  
  $qs = "UPDATE contacts
         SET city = '" . $city . "'
         WHERE id = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
} else {
  if( !is_null( $contact_info[ 'city' ] ) ) {
    $qs = "UPDATE contacts
           SET city = NULL
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* State */
if( $_POST[ 'state' ] ) {
  if( strlen( $_POST[ 'state' ] ) != 2 ) {
    echo( "Invalid State" );
    exit;
  } else {
    $state = mysql_real_escape_string( strtolower( $_POST[ 'state' ] ) );
    
    $qs = "UPDATE contacts
           SET state = '" . $state . "'
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
} else {
  if( !is_null( $contact_info[ 'state' ] ) ) {
    $qs = "UPDATE contacts
           SET state = NULL
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Zipcode */
if( $_POST[ 'zipcode' ] ) {
  if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
    echo( "Invalid Zipcode" );
    exit;
  } else {
    $zipcode = mysql_real_escape_string( $_POST[ 'zipcode' ] );
    
    $qs = "UPDATE contacts
           SET zipcode = " . $zipcode . "
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
} else {
  if( !is_null( $contact_info[ 'zipcode' ] ) ) {
    $qs = "UPDATE contacts
           SET zipcode = NULL
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Apt No */
if( $_POST[ 'aptNo' ] ) {
  $aptno = mysql_real_escape_string( strtolower( $_POST[ 'aptNo' ] ) );
  
  $qs = "UPDATE contacts
         SET apt_no = '" . $aptno . "'
         WHERE id = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
} else {
  if( !is_null( $contact_info[ 'apt_no' ] ) ) {
    $qs = "UPDATE contacts
           SET apt_no = NULL
           WHERE id = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Phone Information */
$hasPhoneInfo = false;

/* Phone Number */
if( $_POST[ 'phone' ] ) {
  if( !ctype_digit( $_POST[ 'phone' ] ) || ( strlen( $_POST[ 'phone' ] ) != 7 && strlen( $_POST[ 'phone' ] ) != 10 ) ) {
    echo( "Invalid Phone" );
    exit;
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

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
    
    $hasPhoneInfo = true;
  }
} else {
  if( $contact_info[ 'phone' ] != 0 ) {
    $qs = "UPDATE contact_phone
           SET phone = 0
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Cell Phone Number */
if( $_POST[ 'cell' ] ) {
  if( !ctype_digit( $_POST[ 'cell' ] ) || ( strlen( $_POST[ 'cell' ] ) != 7 && strlen( $_POST[ 'cell' ] ) != 10 ) ) {
    echo( "Invalid Cell" );
    exit;
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

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
      
    $hasPhoneInfo = true;
  }
} else {
  if( $contact_info[ 'cell' ] != 0 ) {
    $qs = "UPDATE contact_phone
           SET cell = 0
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Text Message Updates */
if( $_POST[ 'textUpdates' ] && $hasPhoneInfo ) {
  $qs = "UPDATE contact_phone
         SET text_updates = 1
         WHERE cid = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
} else {
  if( $contact_info[ 'text_updates' ] == 1 ) {
    $qs = "UPDATE contact_phone
           SET text_updates = 0
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

if( !$hasPhoneInfo ) {
  $qs = "DELETE
         FROM contact_phone
         WHERE cid = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
}

/* Email */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    echo( "Invalid Email" );
    exit;
  } else {
    $email = mysql_real_escape_string( strtolower( $_POST[ 'email' ] ) );
    
    if( !is_null( $contact_info[ 'email' ] ) ) {
      $qs = "UPDATE contact_email
             SET email = '" . $email . "'
             WHERE cid = " . $id;
      
      $qr = mysql_query( $qs, $mc );

      if( !$qr ) {
        echo( "SQL Error " . mysql_error() );
        exit;
      }
    } else {
      $qs = "INSERT INTO contact_email
            ( cid, email )
            VALUES ( " . $id . ", '" . $email . "' )";

      $qr = mysql_query( $qs, $mc );

      if( !$qr ) {
        echo( "SQL Error " . mysql_error() );
        exit;
      }
    }
  }
} else {
  if( !is_null( $contact_info[ 'email' ] ) ) {
    $qs = "DELETE
           FROM contact_email
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
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

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
    
  $hasWorkerInfo = true;
} else {
  if( !is_null( $contact_info[ 'employer' ] ) ) {
    $qs = "UPDATE workers
           SET employer = NULL
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
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

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }

  $hasWorkerInfo = true;
} else {
  if( $hasWorkerInfo || !is_null( $contact_info[ 'wage_below_10' ] ) ) {
    $qs = "UPDATE workers
           SET wage_below_10 = NULL
           WHERE cid = " . $id;
    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Wage */
if( $_POST[ 'dollars' ] ) {
  if( !ctype_digit( $_POST[ 'dollars' ] ) ) {
    echo( "Invalid Dollars" );
    exit;
  } else {
    if( $_POST[ 'cents' ] ) {
      if( !ctype_digit( $_POST[ 'cents' ] ) || strlen( $_POST[ 'cents' ] ) != 2 ) {
        echo( "Invalid Cents" );
        exit;
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

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
    
  $hasWorkerInfo = true;
} else {
  if( !is_null( $contact_info[ 'wage' ] ) ) {
    $qs = "UPDATE workers
           SET wage = NULL
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

if( !$hasWorkerInfo ) {
  $qs = "DELETE
         FROM workers
         WHERE cid = " . $id;

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
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

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
  
  $hasStudentInfo = true;
} else {
  if( !is_null( $contact_info[ 'school' ] ) ) {
    $qs = "DELETE
           FROM students
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* School Year */
if( $_POST[ 'syear' ] ) {
  if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
    echo( "Invalid School Year" );
    exit;
  } else {
    $syear = mysql_real_escape_string( $_POST[ 'syear' ] );
  
    if( $hasStudentInfo || !is_null( $contact_info[ 'syear' ] ) ) {
      $qs = "UPDATE students
             SET syear = " . $syear . "
             WHERE cid = " . $id;
    
      $qr = mysql_query( $qs, $mc );

      if( !$qr ) {
        echo( "SQL Error " . mysql_error() );
        exit;
      }
    }
  }
} else {
  if( !is_null( $contact_info[ 'syear' ] ) ) {
    $qs = "UPDATE students
           SET syear = NULL
           WHERE cid = " . $id;

    $qr = mysql_query( $qs, $mc );

    if( !$qr ) {
      echo( "SQL Error " . mysql_error() );
      exit;
    }
  }
}

/* Return success */
echo( "Success" );

?>
