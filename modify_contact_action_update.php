<?php
/* File: modify_contact_action_update.php
 * Author: Amr Gaber
 * Created: 15/10/2012
 * Description: Handles updating contact in KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Must have privilege level of 2 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 2 ) {
  echo( "Permission Denied" );
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Get ID */
if( !isset( $_POST[ 'id' ] ) ) {
  echo( "Invalid ID" );
  exit;
}

$id = mysql_real_escape_string( $_POST[ 'id' ] );

/* First Name and Last Name */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  echo( "Invalid Name" );
  exit;
} else {
  $firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
  $lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );
}

/* Worker information */
if( $_POST[ 'wageBelow10' ] ) {
  $wagebelow10 = "1";
} else {
  $wagebelow10 = "0";
}

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
} else {
  $wage = 0;
}


if( $_POST[ 'employer' ] ) {
  $employer = mysql_real_escape_string( strtolower( $_POST[ 'employer' ] ) );
} else {
  $employer = "";
}

/* Student information */
if( $_POST[ 'school' ] ) {
  $school = mysql_real_escape_string( strtolower( $_POST[ 'school' ] ) );

  if( $_POST[ 'syear' ] ) {
    if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
      echo( "Invalid School Year" );
      exit;
    } else {
      $syear = mysql_real_escape_string( $_POST[ 'syear' ] );
    }
  } else {
    $syear = 0;
  }
} else {
  $school = "";
  $syear  = 0;
}

/* Contact type */
$contactType = mysql_real_escape_string( strtolower( $_POST[ 'contactType' ] ) );

/* Address */
if( $_POST[ 'address' ] ) {
  $address = mysql_real_escape_string( strtolower( $_POST[ 'address' ] ) );
} else {
  $address = "";
}

if( $_POST[ 'city' ] ) {
  $city = mysql_real_escape_string( strtolower( $_POST[ 'city' ] ) );
} else {
  $city = "";
}

if( $_POST[ 'state' ] ) {
  if( strlen( $_POST[ 'state' ] ) != 2 ) {
    echo( "Invalid State" );
    exit;
  } else {
    $state = mysql_real_escape_string( strtolower( $_POST[ 'state' ] ) );
  }
} else {
  $state = "";
}

if( $_POST[ 'zipcode' ] ) {
  if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
    echo( "Invalid Zipcode" );
    exit;
  } else {
    $zipcode = mysql_real_escape_string( $_POST[ 'zipcode' ] );
  }
} else {
  $zipcode = "";
}

if( $_POST[ 'aptNo' ] ) {
  $aptno = mysql_real_escape_string( strtolower( $_POST[ 'aptNo' ] ) );
} else {
  $aptno = "";
}

/* Phone and cell phone */
if( $_POST[ 'phone' ] ) {
  if( !ctype_digit( $_POST[ 'phone' ] ) || ( strlen( $_POST[ 'phone' ] ) != 7 && strlen( $_POST[ 'phone' ] ) != 10 ) ) {
    echo( "Invalid Phone" );
    exit;
  } else {
    $phone = mysql_real_escape_string( $_POST[ 'phone' ] );
  }
} else {
  $phone = 0;
}

if( $_POST[ 'cell' ] ) {
  if( !ctype_digit( $_POST[ 'cell' ] ) || ( strlen( $_POST[ 'cell' ] ) != 7 && strlen( $_POST[ 'cell' ] ) != 10 ) ) {
    echo( "Invalid Cell" );
    exit;
  } else {
    $cell = mysql_real_escape_string( $_POST[ 'cell' ] );
  }

} else {
  $cell = 0;
}

if( $_POST[ 'textUpdates' ] ) {
  $textupdates = "1";
} else {
  $textupdates = "0";
}

/* Email */
if( $_POST[ 'email' ] ) {
  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    echo( "Invalid Email" );
    exit;
  } else {
    $email = mysql_real_escape_string( strtolower( $_POST[ 'email' ] ) );
  }
} else {
  $email = "";
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Update Entry */
$qs = "UPDATE contacts
       SET first_name = '" . $firstname . "',
         last_name = '" . $lastname . "',
         contact_type = '" . $contactType . "',
         street_no = '" . $address . "',
         city = '" . $city . "',
         state = '" . $state . "',
         zipcode = '" . $zipcode . "',
         apt_no = '" . $aptno . "'
       WHERE contacts.id = " . $id;
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error : " . mysql_error() );
  exit;
}

/* Phone Information */
$qs = "SELECT cid
       FROM contact_phone
       WHERE cid = " . $id;
$qr = mysql_query( $qs, $mc );

if( mysql_num_rows( $qr ) == 1 ) {
  $qs = "UPDATE contact_phone
         SET phone = " . $phone . ",
           cell = " . $cell . ",
           text_updates = " . $textupdates . "
         WHERE cid = " . $id;
} else {
  if( $phone != 0 || $cell != 0 ) {
    $qs = "INSERT INTO contact_phone
           (cid, phone, cell, text_updates)
           VALUES
           (" . $id . ", " . $phone . ", " . $cell . ", " . $textupdates . ")";
  }
}

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error" );
  exit;
}

/* Email Information */
$qs = "SELECT cid
       FROM contact_email
       WHERE cid = " . $id;
$qr = mysql_query( $qs, $mc );

if( mysql_num_rows( $qr ) == 1 ) {
  $qs = "UPDATE contact_email
         SET email = '" . $email . "'
         WHERE cid = " . $id;
} else {
  if( strcmp( $email, "" ) != 0 ) {
    $qs = "INSERT INTO contact_email
           (cid, email)
           VALUES
           (" . $id . ", '" . $email . "')";
  }
}

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error" );
  exit;
}

/* Worker Information */
$qs = "SELECT cid
       FROM workers
       WHERE cid = " . $id;
$qr = mysql_query( $qs, $mc );

if( mysql_num_rows( $qr ) == 1 ) {
  $qs = "UPDATE workers
         SET wage_below_10 = " . $wagebelow10 . ",
           wage = '" . $wage . "',
           employer = '" . $employer . "'
         WHERE cid = " . $id;
} else {
  if( $wagebelow10 != 0 || strcmp( $wage, "" ) != 0 || strcmp( $employer, "" ) != 0 ) {
    $qs = "INSERT INTO workers
           (cid, wage_below_10, wage, employer)
           VALUES
           (" . $id . ", " . $wagebelow10 . ", " . $wage . ", '" . $employer . "')";
  }
}

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error" );
  exit;
}

/* Student Information */
$qs = "SELECT cid
       FROM students
       WHERE cid = " . $id;
$qr = mysql_query( $qs, $mc );

if( mysql_num_rows( $qr ) == 1 ) {
  $qs = "UPDATE students
         SET school = '" . $school ."',
           syear = " . $syear . "
         WHERE cid = " . $id;
} else {
  if( strcmp( $school, "" ) != 0 || $syear != 0 ) {
    $qs = "INSERT INTO students
           (cid, school, syear)
           VALUES
           (" . $id . ", '" . $school . "', " . $syear . ")";
  }
}

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error" );
  exit;
}

/* Return success */
echo( "Success" );

?>
