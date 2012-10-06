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

/* Parse and sanitize $_POST[] input */

/* First Name and Last Name */
if( !$_POST[ 'firstName' ] || $_POST[ 'firstName' ] == "" || !$_POST[ 'lastName' ] || $_POST[ 'lastName' ] == "" ) {
  echo( "Invalid Name" );
  exit;
} else {
  $firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
  $lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );
  
  $contacts_columns = "first_name, last_name";
  $contacts_values  = "'" . $firstname . "', '" . $lastname . "'";
}

/* Worker information */
$isWorker = false;

if( $_POST[ 'wageBelow10' ] ) {
  $wagebelow10 = "1";
} else {
  $wagebelow10 = "0";
}

$workers_columns = "wage_below_10";
$workers_values  = $wagebelow10;

if( $_POST[ 'dollars' ] ) {
  $isWorker = true;

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
  $workers_values  .= ", '" . $wage . "'";
}


if( $_POST[ 'employer' ] ) {
  $isWorker         = true;
  $workplace        = mysql_real_escape_string( strtolower( $_POST[ 'employer' ] ) );
  $workers_columns .= ", wname";
  $workers_values  .= ", '" . $workplace . "'";
}

/* Student information */
$isStudent = false;

if( $_POST[ 'school' ] ) {
  $isStudent = true;
  $school    = mysql_real_escape_string( strtolower( $_POST[ 'school' ] ) );

  if( $_POST[ 'year' ] ) {
    if( !ctype_digit( $_POST[ 'year' ] ) || strlen( $_POST[ 'year' ] ) != 2 ) {
      echo( "Invalid Year" );
      exit;
    } else {
      /* school and year */
      $year             = mysql_real_escape_string( $_POST[ 'year' ] );
      $students_columns = "school, year";
      $students_values  = "'" . $school . "', '20" . $year . "'";
    }
  } else {
    /* school */
    $students_columns = "school";
    $students_values  = "'" . $school . "'";
  }
}

/* Contact type */
if( $isWorker ) {
  $contacts_columns .= ", contact_type";
  $contacts_values  .= ", 'worker'";
} else {
  if( $isStudent ) {
    $contacts_columns .= ", contact_type";
    $contacts_values  .= ", 'student'";
  }
}

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
    $contacts_values  .= ", '" . $zipcode . "'";
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

  if( ctype_digit( $_POST[ 'phone' ] ) && strlen( $_POST[ 'phone' ] ) == 7 ) {
    /* phone number only */
    $phone          = mysql_real_escape_string( $_POST[ 'phone' ] );
    $phone_columns .= "phone";
    $phone_values  .= "'" . $phone . "'";
  } else if( ctype_digit( $_POST[ 'phone' ] ) && strlen( $_POST[ 'phone' ] ) == 10 ) {
    /* area code and phone */
    $phone          = mysql_real_escape_string( $_POST[ 'phone' ] );
    $phone_columns .= "area_code, phone";
    $phone_values  .= "'" . substr( $phone, 0, 3 ) . "', '" . substr( $phone, 3 ) . "'";
  } else {
    echo( "Invalid Phone" );
    exit;
  }

  $phone_columns .= ", cell";
  $phone_values  .= ", 0";
}

$hasCellInfo = false;

if( $_POST[ 'cell' ] ) {
  $hasCellInfo = true;
  
  if( ctype_digit( $_POST[ 'phone' ] ) && strlen( $_POST[ 'cell' ] ) == 7 ) {
    /* cell number only */
    $phone         = mysql_real_escape_string( $_POST[ 'cell' ] );
    $cell_columns .= "phone";
    $cell_values  .= "'" . $phone . "'";
  } else if( ctype_digit( $_POST[ 'phone' ] ) && strlen( $_POST[ 'cell' ] ) == 10 ) {
    /* area code and cell */
    $phone         = mysql_real_escape_string( $_POST[ 'cell' ] );
    $cell_columns .= "area_code, phone";
    $cell_values  .= "'" . substr( $phone, 0, 3 ) . "', '" . substr( $phone, 3 ) . "'";
  } else {
    echo( "Invalid Cell" );
    exit;
  }

  $cell_columns .= ", cell";
  $cell_values  .= ", 1";

  if( $_POST[ 'textUpdates' ] ) {
    $cell_columns .= ", text_updates";
    $cell_values  .= ", 1";
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

  if( ( $row[ 'address' ] == $address
      && $row[ 'city' ] == $city
      && $row[ 'state' ] == $state
      && $row[ 'zipcode' ] == $zipcode )
      || $row[ 'phone' ] == $phone
      || $row[ 'cell' ] == $cell
      || $row[ 'email' ] == $email ) {
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

if( $isWorker ) {
  $qs = "INSERT INTO workers
        ( cid, " . $workers_columns . ")
        VALUES (" . $cid . ", " . $workers_values . ")";
  $qr = mysql_query( $qs, $mc );

  if( !$qr ) {
    echo( "SQL Error");
    exit;
  }
}

if( $isStudent ) {
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

if( $hasCellInfo ) {
  $qs = "INSERT INTO contact_phone
        ( cid, " . $cell_columns . ")
        VALUES (" . $cid . ", " . $cell_values . ")";
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
