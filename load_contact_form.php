<?php
/* File: load_contact_form.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Returns the contact add/update form for KC99 database.
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

/* If id is present, populate form. */
if( $_GET[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify a contact */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to modify a contact." );
  }

  $id = mysql_real_escape_string( $_GET[ 'id' ] );

  /* Get contact information */
  $qs = "SELECT contacts.*,
                contact_phone.*,
                contact_email.*,
                workers.*,
                students.*,
                contact_action.*,
                CONCAT( assigned_organizers.first_name, ' ', assigned_organizers.last_name ) AS assigned_organizer
         FROM contacts
         LEFT JOIN contact_phone  ON contacts.id = contact_phone.cid
         LEFT JOIN contact_email  ON contacts.id = contact_email.cid
         LEFT JOIN workers        ON contacts.id = workers.cid
         LEFT JOIN students       ON contacts.id = students.cid
         LEFT JOIN contact_action ON contacts.id = contact_action.cid
         LEFT JOIN ( 
                   SELECT contact_organizer.cid, contacts.first_name, contacts.last_name
                   FROM contacts, contact_organizer
                   WHERE contacts.id = contact_organizer.oid
                   ) assigned_organizers ON contacts.id = assigned_organizers.cid
         WHERE contacts.id = " . $id;
  
  $qr = execute_query( $qs, $mc );

  $contact_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a contact */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add a contact." );
  }

  $contact_info = Array();
}

?>

<div class="well"> 
  <div class="row-fluid">
    <div class="span1">First Name</div>
    <div class="span5">
      <input type="text" name="firstName" class="span12"
             value="<?php echo( $contact_info[ 'first_name' ] ); ?>"
             placeholder="Type first name here">
    </div>
    
    <div class="span1">Last Name</div>
    <div class="span5">
      <input type="text" name="lastName" class="span12"
             value="<?php echo( $contact_info[ 'last_name' ] ); ?>"
             placeholder="Type last name here">
    </div>
  </div>

  <div class="row-fluid">
    <div class="span1">Address</div>
    <div class="span8">
      <input type="text" name="address" class="span12"
             value="<?php echo( $contact_info[ 'street_no' ] ); ?>"
             placeholder="Type address here">
    </div>

    <div class="span1">Apt. no.</div>
    <div class="span2">
      <input type="text" name="aptNo" class="span12"
             value="<?php echo( $contact_info[ 'apt_no' ] ); ?>"
             placeholder="Type Apt. no. here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">City</div>
    <div class="span6">
      <input type="text" name="city" class="span12"
             value="<?php echo( $contact_info[ 'city' ] ); ?>"
             placeholder="Type city here">
    </div>

    <div class="span1">State</div>
    <div class="span1">
      <input type="text" name="state" class="span12"
             value="<?php echo( $contact_info[ 'state' ] ); ?>"
             placeholder="State">
    </div>
    <div class="span1">Zipcode</div>
    <div class="span2">
      <input type="text" name="zipcode" class="span12"
             value="<?php echo( $contact_info[ 'zipcode' ] ); ?>"
             placeholder="Type zipcode here">
    </div>
  </div>

  <div class="row-fluid">
    <div class="span1">Phone</div>
    <div class="span5">
      <input type="text" name="phone" class="span12"
             value="<?php if( $contact_info[ 'phone' ] != 0 ) { echo( $contact_info[ 'phone' ] ); } ?>"
             placeholder="Type phone number here">
    </div>
    
    <div class="span1">Email</div>
    <div class="span5">
      <input type="text" name="email" class="span12"
             value="<?php echo( $contact_info[ 'email' ] ); ?>"
             placeholder="Type email here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Workplace</div>
    <div class="span5">
      <select id="workplace" class="span12">
        <option></option>
        <?php
          $qs = "SELECT wid,
                        wname,
                        street_no
                 FROM workplaces
                 ORDER BY wname";
          $wqr = execute_query( $qs, $mc );
          
          while( $workplace = mysql_fetch_array( $wqr ) ) { ?>
            <option <?php if( $workplace[ "wid" ] == $contact_info[ "wid" ] ) { echo( "selected" ); } ?>>
              <?php echo( $workplace[ "wname" ] . " " . $workplace[ "street_no" ] . " | " . $workplace[ "wid" ] ); ?>
            </option>
        <?php } ?>
      </select>
    </div>

    <div class="span1">Action</div>
    <div class="span5">
      <select id="action" class="span12">
        <option></option>
        <?php
          $qs = "SELECT aid,
                        aname
                 FROM actions
                 ORDER BY aname";
          $aqr = execute_query( $qs, $mc );
          
          while( $action = mysql_fetch_array( $aqr ) ) { ?>
            <option <?php if( $action[ "aid" ] == $contact_info[ "aid" ] ) { echo( "selected" ); } ?>>
              <?php echo( $action[ "aname" ] . " | " . $action[ "aid" ] ); ?>
            </option>
        <?php } ?>
      </select>
    </div>
  </div>
</div>

<?php

$wage = explode( '.', (string)$contact_info[ 'wage' ] );
$dollars = $wage[ 0 ];
$cents = $wage[ 1 ];

?>

<div class="well">
  <div class="row-fluid">
    <div class="span1">Wage</div>
    <div class="span1 input-prepend">
      <span class="add-on">$</span><input type="text" name="dollars" class="span12"
            value="<?php echo( $dollars ); ?>"
            placeholder="dollars">
    </div>
    <div class="span1 input-prepend">
      <span class="add-on">.</span><input type="text" name="cents" class="span12"
            value="<?php echo( $cents ); ?>"
            placeholder="cents">
    </div>
    
    <div class="span1"></div>

    <div class="span1">School</div>
    <div class="span4">
      <input type="text" name="school" class="span12"
             value="<?php echo( $contact_info[ 'school' ] ); ?>"
             placeholder="Type school here">
    </div>

    <div class="span1">Year</div>
    <div class="span1 input-prepend">
      <span class="add-on">20</span><input type="text" name="syear" class="span12"
            value="<?php echo( $contact_info[ 'syear' ] ); ?>"
            placeholder="year">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Notes</div>
    <div class="span11">
      <textarea name="notes" class="span12" placeholder="Type notes here"><?php echo( $contact_info[ 'notes' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Contact Type</div>    
    <div class="span2">
      <select id="contactType" class="span12">
        <?php $contact_type = $contact_info[ "contact_type" ]; if( !$_GET[ 'id' ] ) { $contact_type = 1; } ?>
        
        <option <?php if( $contact_type == 1 ) { echo( "selected" ); } ?>>Worker</option>
        <option <?php if( $contact_type == 2 ) { echo( "selected" ); } ?>>Student</option>
        <option <?php if( $contact_type == 3 ) { echo( "selected" ); } ?>>Supporter</option>
        <option <?php if( $contact_type == 0 ) { echo( "selected" ); } ?>>Other</option>
      </select>
    </div>
    
    <div class="span2">
      <span class="span4">Date</span>
      <input type="text" name="date" id="date" class="span8" value="<?php echo( $contact_info[ 'cdate' ] ); ?>">
    </div>
  </div>
</div>
