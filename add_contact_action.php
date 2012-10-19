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

/* Parse and sanitize $_POST[] input */

/* First Name and Last Name */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  echo( "Invalid Name" );
  exit;
} else {
  $firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
  $lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );
  
  $contacts_columns = "first_name, last_name";
  $contacts_values  = "'" . $firstname . "', '" . $lastname . "'";
}

/* Worker information */
$hasWorkerInfo = false;

if( $_POST[ 'wageBelow10' ] ) {
  $wagebelow10 = "1";
} else {
  $wagebelow10 = "0";
}

$workers_columns = "wage_below_10";
$workers_values  = $wagebelow10;

if( $_POST[ 'dollars' ] ) {
  $hasWorkerInfo = true;

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

  $workers_columns .= ", wage";
  $workers_values  .= ", " . $wage;
}


if( $_POST[ 'employer' ] ) {
  $hasWorkerInfo         = true;
  $employer         = mysql_real_escape_string( strtolower( $_POST[ 'employer' ] ) );
  $workers_columns .= ", employer";
  $workers_values  .= ", '" . $employer . "'";
}

/* Student information */
$hasStudentInfo = false;

if( $_POST[ 'school' ] ) {
  $hasStudentInfo = true;
  $school    = mysql_real_escape_string( strtolower( $_POST[ 'school' ] ) );

  if( $_POST[ 'syear' ] ) {
    if( !ctype_digit( $_POST[ 'syear' ] ) || strlen( $_POST[ 'syear' ] ) != 2 ) {
      echo( "Invalid School Year" );
      exit;
    } else {
      /* school and year */
      $syear            = mysql_real_escape_string( $_POST[ 'syear' ] );
      $students_columns = "school, syear";
      $students_values  = "'" . $school . "', 20" . $syear;
    }
  } else {
    /* school */
    $students_columns = "school";
    $students_values  = "'" . $school . "'";
  }
}

/* Contact type */
$contactType = mysql_real_escape_string( strtolower( $_POST[ 'contactType' ] ) );
$contacts_columns .= ", contact_type";
$contacts_values  .= ", '". $contactType . "'";

/* Address */
if( $_POST[ 'address' ] ) {
  $address           = mysql_real_escape_string( strtolower( $_POST[ 'address' ] ) );
  $contacts_columns .= ", street_no";
  $contacts_values  .= ", '" . $address . "'";
}

if( $_POST[ 'city' ] ) {
  $city              = mysql_real_escape_string( strtolower( $_POST[ 'city' ] ) );
  $contacts_columns .= ", city";
  $contacts_values  .= ", '" . $city. "'";
}

if( $_POST[ 'state' ] ) {
  if( strlen( $_POST[ 'state' ] ) != 2 ) {
    echo( "Invalid State" );
    exit;
  } else {
    $state             = mysql_real_escape_string( strtolower( $_POST[ 'state' ] ) );
    $contacts_columns .= ", state";
    $contacts_values  .= ", '" . $state . "'";
  }
}

if( $_POST[ 'zipcode' ] ) {
  if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
    echo( "Invalid Zipcode" );
    exit;
  } else {
    $zipcode           = mysql_real_escape_string( $_POST[ 'zipcode' ] );
    $contacts_columns .= ", zipcode";
    $contacts_values  .= ", " . $zipcode;
  }
}

if( $_POST[ 'aptNo' ] ) {
  $aptno             = mysql_real_escape_string( strtolower( $_POST[ 'aptNo' ] ) );
  $contacts_columns .= ", apt_no";
  $contacts_values  .= ", '" . $aptno . "'";
}

/* Phone and cell phone */
$hasPhoneInfo = false;

if( $_POST[ 'phone' ] ) {
  $hasPhoneInfo = true;

  if( !ctype_digit( $_POST[ 'phone' ] ) || ( strlen( $_POST[ 'phone' ] ) != 7 && strlen( $_POST[ 'phone' ] ) != 10 ) ) {
    echo( "Invalid Phone" );
    exit;
  } else {
    $phone         = mysql_real_escape_string( $_POST[ 'phone' ] );
    $phone_columns = "phone";
    $phone_values  = $phone;
  }
}

if( $_POST[ 'cell' ] ) {
  if( !ctype_digit( $_POST[ 'cell' ] ) || ( strlen( $_POST[ 'cell' ] ) != 7 && strlen( $_POST[ 'cell' ] ) != 10 ) ) {
    echo( "Invalid Cell" );
    exit;
  } else {
    $cell = mysql_real_escape_string( $_POST[ 'cell' ] );

    if( $hasPhoneInfo ) {
      $phone_columns .= ", cell";
      $phone_values  .= ", " . $cell;
    } else {
      $hasPhoneInfo  = true;
      $phone_columns = "cell";
      $phone_values  = $cell;
    }
  }

  if( $_POST[ 'textUpdates' ] ) {
    $phone_columns .= ", text_updates";
    $phone_values  .= ", 1";
  }
}

/* Email */
$hasEmailInfo = false;

if( $_POST[ 'email' ] ) {
  $hasEmailInfo = true;

  if( !strpos( $_POST[ 'email' ], '@' ) || !strpos( $_POST[ 'email' ], '.' ) ) {
    echo( "Invalid Email" );
    exit;
  } else {
    $email          = mysql_real_escape_string( strtolower( $_POST[ 'email' ] ) );
    $email_columns .= "email";
    $email_values  .= "'" . $email . "'";
  }
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Check for duplicate */
$qs = "SELECT first_name, last_name
       FROM contacts
       WHERE first_name='" . $firstname . "' AND last_name='" . $lastname . "'";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

$row = mysql_fetch_array( $qr );

if( $row[ 'first_name' ] && $row[ 'last_name' ] ) {
  $qs = "SELECT *
         FROM contacts
         WHERE first_name='" . $firstname . "' AND last_name='" . $lastname . "'";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }

  $row = mysql_fetch_array( $qr );

  if( (  $row[ 'address' ] == $address
      && $row[ 'city'    ] == $city
      && $row[ 'state'   ] == $state
      && $row[ 'zipcode' ] == $zipcode )
      || $row[ 'phone'   ] == $phone
      || $row[ 'cell'    ] == $cell
      || $row[ 'email'   ] == $email ) {
    echo( "Duplicate Entry" );
    exit;
  }
}

/* Add to database */
$qs = "INSERT INTO contacts
      (" . $contacts_columns . ")
      VALUES (" . $contacts_values . ")";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

/* Get id of the contact that was just added */
$qs = "SELECT id
       FROM contacts
       WHERE first_name='" . $firstname . "' AND last_name='" . $lastname. "'
       ORDER BY id DESC
       LIMIT 1";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

$row = mysql_fetch_array( $qr );
$cid = $row[ 'id' ];

if( $hasWorkerInfo ) {
  $qs = "INSERT INTO workers
        ( cid, " . $workers_columns . ")
        VALUES (" . $cid . ", " . $workers_values . ")";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }
}

if( $hasStudentInfo ) {
  $qs = "INSERT INTO students
        ( cid, " . $students_columns . ")
        VALUES (" . $cid . ", " . $students_values . ")";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }
}

if( $hasPhoneInfo ) {
  $qs = "INSERT INTO contact_phone
        ( cid, " . $phone_columns . ")
        VALUES (" . $cid . ", " . $phone_values . ")";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }
}

if( $hasEmailInfo ) {
  $qs = "INSERT INTO contact_email
        ( cid, " . $email_columns . ")
        VALUES (" . $cid . ", " . $email_values . ")";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }
}

/* Return success */
echo( "Success" );

?>
