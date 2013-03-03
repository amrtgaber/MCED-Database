<?php
/* File: load_contact_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/3
 * Description: Returns the contact action form for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

include( "db_credentials.php" );
include( "common.php" );

/* Connect to database */
$mc = connect_to_database();

/* Must have privilege level of 1 or greater to add a contact to an action */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a contact to an action." );
}

?>

<div class="well"> 
  <div class="row-fluid">
    <div class="span2">Action Name</div>
    <div class="span10">
      <input type="text" id="aname" name="aname" class="span12" placeholder="Type action name here" data-provide="typeahead" data-source="[
             <?php
             $qs = "SELECT actions.*
                    FROM actions
                    ORDER BY actions.aname";
             
             $aqr = execute_query( $qs, $mc );
             
             $action_info = mysql_fetch_array( $aqr );
             echo( '&#34;' . str_replace( '"', "'", $action_info[ "aname" ] . " | " . $action_info[ "aid" ] ) . '&#34;' );
             
             while( $action_info = mysql_fetch_array( $aqr ) ) {
               echo( ', &#34;' . str_replace( '"', "'", $action_info[ "aname" ] . " | " . $action_info[ "aid" ] ) . '&#34;' );
             } ?>
             ]" required>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span2">Contact Name</div>
    <div class="span10">
      <input type="text" class="span12" id="cname" name="cname" placeholder="Type contact name here" data-provide="typeahead" data-items="50" data-source="[
             <?php
             $qs = "SELECT contacts.*
                    FROM contacts
                    ORDER BY contacts.last_name";
             
             $cqr = execute_query( $qs, $mc );
             
             $contact_info = mysql_fetch_array( $cqr );
             echo( '&#34;' . str_replace( '"', "'", $contact_info[ "first_name" ] . " " . $contact_info[ "last_name" ] . " | " . $contact_info[ "street_no" ] . " | " . $contact_info[ "id" ] ) . '&#34;' );
             
             while( $contact_info = mysql_fetch_array( $cqr ) ) {
               echo( ', &#34;' . str_replace( '"', "'", $contact_info[ "first_name" ] . " " . $contact_info[ "last_name" ] . " | " . $contact_info[ "street_no" ] . " | " . $contact_info[ "id" ] ) . '&#34;' );
             } ?>
             ]" required>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Date</div>
    <div class="span3">
      <input type="text" name="date" id="date" class="span12" required>
    </div>
  </div>
</div>
