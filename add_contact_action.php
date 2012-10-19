<?php
/* File: add_contact_action.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Handles adding contact into KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Must have privilege level of 1 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  echo( "Permission Denied" );
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
  
/* Add to database */
$firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );

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

$row = mysql_fetch_array( $qr );
$id = $row[ 'id' ];

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

    if( $hasPhoneInfo ) {
      $qs = "UPDATE contact_phone
             SET phone = " . $phone . "
             WHERE cid = " . $id;
    } else {
      $hasPhoneInfo = true;
      $qs = "INSERT INTO contact_phone
            ( cid, phone )
            VALUES ( " . $id . ", " . $phone . " )";
    }

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

    if( $hasPhoneInfo ) {
      $qs = "UPDATE contact_phone
             SET cell = " . $cell . "
             WHERE cid = " . $id;
    } else {
      $hasPhoneInfo = true;
      $qs = "INSERT INTO contact_phone
            ( cid, cell )
            VALUES ( " . $id . ", " . $cell . " )";
    }

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
}

/* Email */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    echo( "Invalid Email" );
    exit;
  } else {
    $email = mysql_real_escape_string( strtolower( $_POST[ 'email' ] ) );
    
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

/* Worker information */
$hasWorkerInfo = false;

/* Employer */
if( $_POST[ 'employer' ] ) {
  $employer = mysql_real_escape_string( strtolower( $_POST[ 'employer' ] ) );

  if( $hasWorkerInfo ) {
    $qs = "UPDATE workers
           SET employer = '" . $employer . "'
           WHERE cid = " . $id;
  } else {
    $hasWorkerInfo = true;
    $qs = "INSERT INTO workers
          ( cid, employer )
          VALUES ( " . $id . ", '" . $employer . "' )";
  }

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
}

/* Wage below $10 / hour */
if( $_POST[ 'wageBelow10' ] ) {
  $wagebelow10 = 1;
  
  if( $hasWorkerInfo ) {
    $qs = "UPDATE workers
           SET wage_below_10 = " . $wagebelow10 . "
           WHERE cid = " . $id;
  } else {
    $hasWorkerInfo = true;
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
  $wagebelow10 = 0;
  
  if( $hasWorkerInfo ) {
    $qs = "UPDATE workers
           SET wage_below_10 = " . $wagebelow10 . "
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

  if( $hasWorkerInfo ) {
    $qs = "UPDATE workers
           SET wage = " . $wage . "
           WHERE cid = " . $id;
  } else {
    $hasWorkerInfo = true;
    $qs = "INSERT INTO workers
          ( cid, wage )
          VALUES ( " . $id . ", " . $wage . " )";
  }

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

  if( $hasStudentInfo ) {
    $qs = "UPDATE students
           SET school = '" . $school . "'
           WHERE cid = " . $id;
  } else {
    $hasStudentInfo = true;
    $qs = "INSERT INTO students
          ( cid, school )
          VALUES ( " . $id . ", '" . $school . "' )";
  }

  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error " . mysql_error() );
    exit;
  }
}

/* School Year */
if( $_POST[ 'syear' ] ) {
  if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
    echo( "Invalid School Year" );
    exit;
  } else {
    $syear = mysql_real_escape_string( $_POST[ 'syear' ] );
  
    if( $hasStudentInfo ) {
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
}

/* Return success */
echo( "Success" );

?>
