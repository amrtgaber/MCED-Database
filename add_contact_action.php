<?php
/* File: add_contact_action.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Handles adding contact into KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION['username']) {
  header("HTTP/1.1 401 Unauthorized");
  exit;
}

/* Parse and sanitize $_POST[] input */

/* Check for errors */

/* Connect to database */
$mc = mysql_connect("localhost", "root", "debrijjadb") or die(mysql_error());
mysql_select_db("kc99");

/* Check for duplicate */
//$qr = mysql_query("SELECT first_name, last_name FROM contacts WHERE first_name='" . $firstName . "' AND last_name='" . $lastName . "'", $mc);
//$row = mysql_fetch_array($qr);
//$var = $row['username'];

/* Add to database */

/* Return success */
header("HTTP/1.1 200 OK");

?>
