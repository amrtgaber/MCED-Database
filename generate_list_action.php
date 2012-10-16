<?php
/* File: generate_list_action.php
 * Author: Amr Gaber
 * Created: 06/10/2012
 * Description: Handles generating list for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  echo( "Unauthorized" );
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Parse contact type */
$contactType = "";

if( $_POST[ 'workers' ] ) {
  $contactType = "contacts.contact_type = 'worker'";
}

if( $_POST[ 'students' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'student'";
  } else {
    $contactType .= " OR contacts.contact_type = 'student'";
  }
}

if( $_POST[ 'supporters' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'supporter'";
  } else {
    $contactType .= " OR contacts.contact_type = 'supporter'";
  }
}

if( $_POST[ 'organizers' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'organizer'";
  } else {
    $contactType .= " OR contacts.contact_type = 'organizer'";
  }
}

if( $_POST[ 'other' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'other'";
  } else {
    $contactType .= " OR contacts.contact_type = 'other'";
  }
}

if( $contactType == "" ) {
  echo( "Invalid People" );
  exit;
}

/* Parse info and build response html */
$selection  = "contacts.first_name, contacts.last_name";
$joinString = "";
$html = "<thead><tr><th>Last Name</th><th>First Name</th>";

if( $_POST[ 'contactType' ] ) {
  $selection .= ", contacts.contact_type";
  $html      .= "<th>Contact Type</th>";
}

if( $_POST[ 'assessment' ] ) {
  $selection .= ", contacts.assessment";
  $html      .= "<th>Assessment</th>";
}

if( $_POST[ 'address' ] ) {
  $selection .= ", contacts.street_no, contacts.apt_no, contacts.city, contacts.state, contacts.zipcode";
  $html      .= "<th>Address</th>";
}

if( $_POST[ 'phoneNumber' ] ) {
  $selection  .= ", contact_phone.phone, contact_phone.cell";
  $joinString .= " LEFT JOIN contact_phone ON contacts.id = contact_phone.cid";
  $html       .= "<th>Phone</th><th>Cell</th>";
}

if( $_POST[ 'email' ] ) {
  $selection  .= ", contact_email.email";
  $joinString .= " LEFT JOIN contact_email ON contacts.id = contact_email.cid";
  $html       .= "<th>Email</th>";
}

if( $_POST[ 'wage' ] || $_POST[ 'workplace' ] ) {
  $joinString .= " LEFT JOIN workers ON contacts.id = workers.cid";

  if( $_POST[ 'wage' ] ) {
    $selection .= ", workers.wage";
    $html      .= "<th>Wage</th>";
  }

  if( $_POST[ 'workplace' ] ) {
    $selection  .= ", workers.employer, workplaces.wname";
    $joinString .= " LEFT JOIN workplaces ON workers.wid = workplaces.wid";
    $html       .= "<th>Workplace</th>";
  }
}

if( $_POST[ 'school' ] || $_POST[ 'schoolYear' ] ) {
  $joinString .= " LEFT JOIN students ON contacts.id = students.cid";

  if( $_POST[ 'school'] ) {
    $selection .= ", students.school";
    $html      .= "<th>School</th>";
  }

  if( $_POST[ 'schoolYear' ] ) {
    $selection .= ", students.syear";
    $html      .= "<th>School Year</th>";
  }
}

if( $_POST[ 'assignedOrganizer' ] ) {
  $selection  .= ", CONCAT( "
                 . "assigned_organizers.first_name, ' ', assigned_organizers.last_name ) "
                 . "AS assigned_organizer";

  $joinString .= " LEFT JOIN ( "
                 . "SELECT contact_organizer.cid, contacts.first_name, contacts.last_name "
                 . "FROM contacts, contact_organizer "
                 . "WHERE contacts.id = contact_organizer.oid "
                 . ") assigned_organizers ON contacts.id = assigned_organizers.cid";

  $html       .= "<th>Assigned Organizer</th>";
}

$html .= "</tr></thead><tbody>";

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Select desired information */
$qs = "SELECT " . $selection . " "
      . "FROM contacts "
      . $joinString . " "
      . "WHERE " . $contactType . " "
      . "ORDER BY contacts.last_name"; /* echo( $qs ); */
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error" );
  exit;
}

while( $row = mysql_fetch_array( $qr ) ) {
  $html .= "<tr><td>" . ucwords( $row[ 'last_name' ] ) . "</td><td>" . ucwords( $row[ 'first_name' ] ) . "</td>";

  if( $_POST[ 'contactType' ] ) {
    if( $row[ 'contact_type' ] ) {
      $html .= "<td>" . $row[ 'contact_type' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'assessment' ] ) {
    if( $row[ 'assessment' ] ) {
      $html .= "<td>" . $row[ 'assessment' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'address' ] ) {
    if( $row[ 'street_no' ] ) {
      $address = ucwords( $row[ 'street_no' ] );

      if( $row[ 'apt_no' ] ) {
        $address .= " Apt. " . $row[ 'apt_no' ];
      }

      $address .= ", " . ucwords( $row[ 'city' ] ) . ", " . strtoupper( $row[ 'state' ] ) . " " . $row[ 'zipcode' ];
      $html    .= "<td>" . $address . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'phoneNumber' ] ) {
    if( $row[ 'phone' ] ) {
      $html .= "<td>" . $row[ 'phone' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }

    if( $row[ 'cell' ] ) {
      $html .= "<td>" . $row[ 'cell' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'email' ] ) {
    if( $row[ 'email' ] ) {
      $html .= "<td>" . $row[ 'email' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'wage' ] ) {
    if( $row[ 'wage' ] ) {
      $html .= "<td>$" . $row[ 'wage' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'workplace' ] ) {
    if( $row[ 'wname' ] ) {
      $html .= "<td>" . ucwords( $row[ 'wname' ] ) . "</td>";
    } else if( $row[ 'employer' ] ) {
      $html .= "<td>" . ucwords( $row[ 'employer' ] ) . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'school' ] ) {
    if( $row[ 'school' ] ) {
      $html .= "<td>" . strtoupper( $row[ 'school' ] ) . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'schoolYear' ] ) {
    if( $row[ 'syear' ] ) {
      $html .= "<td>" . $row[ 'syear' ] . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  if( $_POST[ 'assignedOrganizer' ] ) {
    if( $row[ 'assigned_organizer' ] ) {
      $html .= "<td>" . ucwords( $row[ 'assigned_organizer' ] ) . "</td>";
    } else {
      $html .= "<td></td>";
    }
  }

  $html .= "</tr>";
}

/* Construct table body html */
$html .= "</tbody>";

echo( $html );

?>

