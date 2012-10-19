<?php
/* File: modify_contact_action_select.php
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

/* Get ID */
if( !isset( $_POST[ 'id' ] ) ) {
  echo( "Invalid ID" );
  exit;
}

$id = mysql_real_escape_string( $_POST[ 'id' ] );

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Search for entry */
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
       WHERE contacts.id = '" . $id . "'";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( "SQL Error");
  exit;
}

$row = mysql_fetch_array( $qr );

$response  = "Success ";
$response .= "<div class=\"well\">";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">First Name</div>";
$response .= "<div class=\"span5\">";
$response .= "<input type=\"text\" name=\"firstName\" class=\"span12\" value=\"" . ucwords( $row[ 'first_name' ] ) . "\" placeholder=\"Type first name here\">";
$response .= "</div>";
                
$response .= "<div class=\"span1\">Last Name</div>";
$response .= "<div class=\"span5\">";
$response .= "<input type=\"text\" name=\"lastName\" class=\"span12\" value=\"" . ucwords( $row[ 'last_name' ] ) . "\" placeholder=\"Type last name here\">";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span3\">";
$response .= "<input type=\"checkbox\" name=\"wageBelow10\" value=\"true\"";
if( isset( $row[ 'wage_below_10' ] ) ) {
  $response .= " checked>";
} else {
  $response .= ">";
}
$response .= "I make less than $10 an hour.";
$response .= "</div>";
                
$response .= "<div class=\"span1\">Employer</div>";
$response .= "<div class=\"span8\">";
if( strcmp( $row[ 'employer' ], "null" ) != 0 ) {
  $employer = ucwords( $row[ 'employer' ] );
} else {
  $employer = "";
}
$response .= "<input type=\"text\" name=\"employer\" class=\"span12\" value=\"" . $employer . "\" placeholder=\"Type employer here\">";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">Address</div>";
$response .= "<div class=\"span8\">";
$response .= "<input type=\"text\" name=\"address\" class=\"span12\" value=\"" . ucwords( $row[ 'street_no' ] ) . "\"placeholder=\"Type address here\">";
$response .= "</div>";

$response .= "<div class=\"span1\">Apt. no.</div>";
$response .= "<div class=\"span2\">";
$response .= "<input type=\"text\" name=\"aptNo\" class=\"span12\" value=\"" . strtoupper( $row[ 'apt_no' ] ) . "\" placeholder=\"Type Apt. no. here\">";
$response .= "</div></div>";
                
$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">City</div>";
$response .= "<div class=\"span6\">";
$response .= "<input type=\"text\" name=\"city\" class=\"span12\" value=\"" . ucwords( $row[ 'city' ] ) . "\" placeholder=\"Type city here\">";
$response .= "</div>";

$response .= "<div class=\"span1\">State</div>";
$response .= "<div class=\"span1\">";
$response .= "<input type=\"text\" name=\"state\" class=\"span12\" value=\"" . strtoupper( $row[ 'state' ] ) . "\" placeholder=\"State\">";
$response .= "</div>";
$response .= "<div class=\"span1\">Zipcode</div>";
$response .= "<div class=\"span2\">";
$response .= "<input type=\"text\" name=\"zipcode\" class=\"span12\" value=\"" . $row[ 'zipcode' ] . "\" placeholder=\"Type zipcode here\">";
$response .= "</div></div>";
                
$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">Phone</div>";
$response .= "<div class=\"span5\">";
if( $row[ 'phone' ] != 0 ) {
  $phone = $row[ 'phone' ];
} else {
  $phone = "";
}
$response .= "<input type=\"text\" name=\"phone\" class=\"span12\" value=\"". $phone . "\" placeholder=\"Type phone number here\">";
$response .= "</div>";
                
$response .= "<div class=\"span1\">Cell</div>";
$response .= "<div class=\"span5\">";
if( $row[ 'cell' ] != 0 ) {
  $cell = $row[ 'cell' ];
} else {
  $cell = "";
}
$response .= "<input type=\"text\" name=\"cell\" class=\"span12\" value=\"". $cell . "\" placeholder=\"Type cell phone number here\">";
$response .= "</div></div>";
                
$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span12\">";
$response .= "<input type=\"checkbox\" name=\"textUpdates\" value=\"true\"";
if( $row[ 'text_updates' ] ) {
  $response .= " checked>";
} else {
  $response .= ">";
}
$response .= "Send me text mssage updates.";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">Email</div>";
$response .= "<div class=\"span11\">";
$response .= "<input type=\"text\" name=\"email\" class=\"span12\" value=\"". $row[ 'email' ] . "\" placeholder=\"Type email here\">";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span12\" id=\"error\"></div>";
$response .= "</div></div>";

$wage = explode( '.', (string)$row[ 'wage' ] );
$dollars = $wage[ 0 ];
$cents = $wage[ 1 ];

$response .= "<div class=\"well\">";
$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">Wage</div>";
$response .= "<div class=\"span1 input-prepend\">";
$response .= "<span class=\"add-on\">$</span><input type=\"text\" name=\"dollars\" class=\"span12\" value=\"" . $dollars . "\" placeholder=\"dollars\">";
$response .= "</div>";
$response .= "<div class=\"span1 input-prepend\">";
$response .= "<span class=\"add-on\">.</span><input type=\"text\" name=\"cents\" class=\"span12\" value=\"" . $cents . "\" placeholder=\"cents\">";
$response .= "</div>";
                
$response .= "<div class=\"span1\"></div>";

$response .= "<div class=\"span1\">School</div>";
$response .= "<div class=\"span4\">";
$response .= "<input type=\"text\" name=\"school\" class=\"span12\" value=\"" . strtoupper( $row[ 'school' ] ) . "\" placeholder=\"Type school here\">";
$response .= "</div>";

$response .= "<div class=\"span1\">Year</div>";
$response .= "<div class=\"span1 input-prepend\">";
if( $row[ 'syear' ] != 0 ) {
  $syear = $row[ 'syear' ];
} else {
  $syear = "";
}
$response .= "<span class=\"add-on\">20</span><input type=\"text\" name=\"syear\" class=\"span12\" value=\"" . $syear . "\" placeholder=\"year\">";
$response .= "</div></div>";
              
$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">Contact Type</div>";
$response .= "<div class=\"span2\">";
$response .= "<select id=\"contactType\">";
if( strcmp( $row[ 'contact_type' ], 'worker' ) == 0 ) {
  $response .= "<option id=\"optionWorker\" selected=\"true\">Worker</option>";
} else {
  $response .= "<option id=\"optionWorker\">Worker</option>";
}
if( strcmp( $row[ 'contact_type' ], 'student' ) == 0 ) {
  $response .= "<option id=\"optionStudent\" selected=\"true\">Student</option>";
} else {
  $response .= "<option id=\"optionStudent\">Student</option>";
}
if( strcmp( $row[ 'contact_type' ], 'supporter' ) == 0 ) {
$response .= "<option id=\"optionSupporter\" selected=\"true\">Supporter</option>";
} else {
$response .= "<option id=\"optionSupporter\">Supporter</option>";
}
if( strcmp( $row[ 'contact_type' ], 'organizer' ) == 0 ) {
$response .= "<option id=\"optionOrganizer\" selected=\"true\">Organizer</option>";
} else {
$response .= "<option id=\"optionOrganizer\">Organizer</option>";
}
$response .= "</select>";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span12\" id=\"error-optional\"></div>";
$response .= "</div></div>";

$response .= "<div class=\"row-fluid\">";
$response .= "<div class=\"span1\">";
$response .= "<button type=\"submit\" class=\"btn btn-primary btn-large\">Update</button>";
$response .= "</div><div class=\"span1\">";
$response .= "<button type=\"button\" id=\"backToSelect\" class=\"btn btn-large\">Back</button>";
$response .= "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\">";
$response .= "</div></div>";

echo( $response );

?>
