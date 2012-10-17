<?php
/* File: modify_contact_action_search.php
 * Author: Amr Gaber
 * Created: 14/10/2012
 * Description: Handles modifying a contact in the KC99 database.
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

/* First Name and Last Name */
if( !isset( $_POST[ 'firstName' ] ) || $_POST[ 'firstName' ] == "" || !isset( $_POST[ 'lastName' ] ) || $_POST[ 'lastName' ] == "" ) {
  echo( "Invalid Name" );
  exit;
}

$firstname = mysql_real_escape_string( strtolower( $_POST[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_POST[ 'lastName' ] ) );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Search for entry */
$qs = "SELECT contacts.*,
              contact_phone.*,
              contact_email.email,
              workers.employer
       FROM contacts
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid
       WHERE contacts.first_name = '" . $firstname . "' AND contacts.last_name = '" . $lastname . "'";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

if( mysql_num_rows( $qr ) > 0 ) {
  $response  = "Success ";
  $response .= "<h4>Please select from the list of results</h4>";

  $response .= "<div class=\"row-fluid\">";

  $response .= "<table class=\"table table-bordered table-striped table-condensed\">";
  $response .= "<thead><tr><th></th>";

  $response .= "<th>";
  $response .= "First Name";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Last Name";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Contact Type";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Address";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Phone";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Cell";
  $response .= "</th>";

  $response .= "<th>";
  $response .= "Email";
  $response .= "</th>";
  
  $response .= "<th>";
  $response .= "Employer";
  $response .= "</th>";
  
  $response .= "</tr></thead><tbody>";

  while( $row = mysql_fetch_array( $qr ) ) {
    $response .= "<tr>";

    $response .= "<td>";
    $response .= "<input type=\"radio\" name=\"id\" value=\"" . $row[ 'id' ] . "\">";
    $response .= "</td>";

    $response .= "<td>";
    $response .= ucwords( $row[ 'first_name' ] );
    $response .= "</td>";

    $response .= "<td>";
    $response .= ucwords( $row[ 'last_name' ] );
    $response .= "</td>";
                
    $response .= "<td>";
    $response .= $row[ 'contact_type' ];
    $response .= "</td>";
                
    $response .= "<td>";
    if( $row[ 'street_no' ] ) {
      $address = ucwords( $row[ 'street_no' ] );
      
      if( $row[ 'apt_no' ] ) {
        $address .= " Apt. " . $row[ 'apt_no' ];
      }
      
      $address .= ", " . ucwords( $row[ 'city' ] ) . ", " . strtoupper( $row[ 'state' ] ) . " " . $row[ 'zipcode' ];

      $response .= $address;
    }
    $response .= "</td>";
                
    $response .= "<td>";
    if( $row[ 'phone' ] != 0 ) {
      $response .= $row[ 'phone' ];
    }
    $response .= "</td>";
   
    $response .= "<td>";
    if( $row[ 'cell' ] != 0 ) {
      $response .= $row[ 'cell' ];
    }
    $response .= "</td>";

    $response .= "<td>";
    $response .= $row[ 'email' ];
    $response .= "</td>";

    $response .= "<td>";
    if( strcmp( $row[ 'employer' ], "null" ) != 0 ) {
      $response .= $row[ 'employer' ];
    }
    $response .= "</td>";
  
    $response .= "</tr>";
  }
  $response .= "</tbody></table>";
  $response .= "</div>";

  $response .= "<div class=\"row-fluid\">";
  $response .= "<div class=\"span1\">";
  if( $_POST[ 'remove' ] ) {
    $response .= "<button type=\"submit\" class=\"btn btn-primary btn-large btn-danger\">Remove</button>";
  } else {
    $response .= "<button type=\"submit\" class=\"btn btn-primary btn-large\">Select</button>";
  }
  $response .= "</div><div class=\"span1\">";
  $response .= "<button type=\"button\" id=\"backToSearch\" class=\"btn btn-large\">Back</button>";
  $response .= "</div></div>";

  echo( $response );
} else {
  echo( "Not Found" );
}

?>
