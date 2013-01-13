<?php
/* File: load_contact_sheet_form.php
 * Author: Amr Gaber
 * Created: 2013/1/10
 * Description: Returns the contact sheet form for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

include( "common.php" );

/* Connect to database */
$mc = connect_to_database();

/* Must have privilege level of 1 or greater to add a contact sheet */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a contact." );
}

/* If id is present, populate form. */
if( !isset( $_GET[ 'id' ] ) ) {
  $contact_info = Array();
} else {
  $id = mysql_real_escape_string( $_GET[ 'id' ] );

  /* Get contact information */
  $qs = "SELECT contacts.*,
                contact_phone.*,
                contact_email.*,
                workers.*,
                students.*,
                contact_sheet.*,
                CONCAT( assigned_organizers.first_name, ' ', assigned_organizers.last_name ) AS assigned_organizer
         FROM contacts
         LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
         LEFT JOIN contact_email ON contacts.id = contact_email.cid
         LEFT JOIN workers       ON contacts.id = workers.cid
         LEFT JOIN workplaces    ON workers.wid = workplaces.wid
         LEFT JOIN students      ON contacts.id = students.cid
         LEFT JOIN contact_sheet ON contacts.id = contact_sheet.cid
         LEFT JOIN ( 
                   SELECT contact_organizer.cid, contacts.first_name, contacts.last_name
                   FROM contacts, contact_organizer
                   WHERE contacts.id = contact_organizer.oid
                   ) assigned_organizers ON contacts.id = assigned_organizers.cid
         WHERE contacts.id = " . $id . "
         ORDER BY contact_sheet.cs_date DESC
         LIMIT 1";

  $qr = execute_query( $qs, $mc );

  $contact_info = mysql_fetch_array( $qr );
}

?>

<div class="well">
  <div class="row-fluid">
    <?php if( !isset( $id ) ) { ?>
      <div class="span1">First Name</div>
      <div class="span3">
        <input type="text" name="firstName" class="span12" placeholder="Type first name here">
      </div>
      
      <div class="span1">Last Name</div>
      <div class="span3">
        <input type="text" name="lastName" class="span12" placeholder="Type last name here">
      </div>
    <?php } else { ?>
      <div class="span4" style="font-size:2em"><?php echo( ucwords( $contact_info[ 'first_name' ] ) ); ?></div>
      
      <div class="span4" style="font-size:2em"><?php echo( ucwords( $contact_info[ 'last_name' ] ) ); ?></div>
    <?php } ?>
    
    <div class="span1">Workplace</div>
    <div class="span3">
      <input type="text" name="workplace" class="span12"
        value="<?php echo( $contact_info[ 'employer' ] ); ?>"
        placeholder="Type workplace here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Job</div>
    <div class="span2">
      <input type="text" name="job" class="span12"
        value="<?php echo( $contact_info[ 'job' ] ); ?>"
        placeholder="Type job here">
    </div>
    
    <div class="span1">Shift</div>
    <div class="span2">
      <input type="text" name="shift" class="span12"
        value="<?php echo( $contact_info[ 'shift' ] ); ?>"
        placeholder="Type shift here">
    </div>
    
    <div class="span1">Cell #</div>
    <div class="span2">
      <input type="text" name="cell" class="span12"
        value="<?php if( $contact_info[ 'cell' ] != 0 ) { echo( $contact_info[ 'cell' ] ); } ?>"
        placeholder="Type cell # here">
    </div>
    
    <div class="span1">Home #</div>
    <div class="span2">
      <input type="text" name="phone" class="span12"
        value="<?php if( $contact_info[ 'phone' ] != 0 ) { echo( $contact_info[ 'phone' ] ); } ?>"  
        placeholder="Type home # here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Address</div>
    <div class="span5">
      <input type="text" name="address" class="span12"
        value="<?php echo( $contact_info[ 'street_no' ] ); ?>"
        placeholder="Type address here">
    </div>
    
    <div class="span1">City</div>
    <div class="span2">
      <input type="text" name="city" class="span12"
        value="<?php echo( $contact_info[ 'city' ] ); ?>"
        placeholder="Type city here">
    </div>
    
    <div class="span1">Zip</div>
    <div class="span2">
      <input type="text" name="zipcode" class="span12"
        value="<?php echo( $contact_info[ 'zipcode' ] ); ?>"
        placeholder="Type zipcode here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Issues</div>
  </div>
  
  <div class="row-fluid">
    <div class="span12">
      <textarea name="issues" class="span12" placeholder="Type issues here"><?php echo( $contact_info[ 'issues' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Reservations</div>
  </div>
  
  <div class="row-fluid">
    <div class="span12">
      <textarea name="reservations" class="span12" placeholder="Type reservations here"><?php echo( $contact_info[ 'reservations' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span2">Leaders Identified</div>
    <div class="span10">
      <input type="text" name="leadersIdentified" class="span12"
        value="<?php echo( $contact_info[ 'leaders' ] ); ?>"
        placeholder="Type leaders identified here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Comments</div>
  </div>
  
  <div class="row-fluid">
    <div class="span12">
      <textarea name="comments" class="span12" placeholder="Type comments here"><?php echo( $contact_info[ 'comments' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span3">Assignments &amp; Follow-Ups</div>
  </div>
  
  <div class="row-fluid">
    <div class="span12">
      <textarea name="assignments" class="span12" placeholder="Type assignments and follow ups here"><?php echo( $contact_info[ 'assignments' ] ); ?></textarea>
    </div>
  </div>
  
  <?php $rating = $contact_info[ 'rating' ]; ?>
  
  <div class="row-fluid">
    <div class="span1">Rating</div>
    <div class="span3 inline">
      <label class="radio inline">1</label><input type="radio" name="rating" value="1" <?php if( $rating == 1 ) { echo( "checked" ); } ?>>
      <label class="radio inline">2</label><input type="radio" name="rating" value="2" <?php if( $rating == 2 ) { echo( "checked" ); } ?>>
      <label class="radio inline">3</label><input type="radio" name="rating" value="3" <?php if( $rating == 3 || !isset( $rating ) ) { echo( "checked" ); } ?>>
      <label class="radio inline">4</label><input type="radio" name="rating" value="4" <?php if( $rating == 4 ) { echo( "checked" ); } ?>>
      <label class="radio inline">5</label><input type="radio" name="rating" value="5" <?php if( $rating == 5 ) { echo( "checked" ); } ?>>
    </div>

    <div class="span2">
      <label class="checkbox inline">Story</label>
      <input type="checkbox" name="story" value="true" <?php if( isset( $contact_info[ "story" ] ) ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Video</label>
      <input type="checkbox" name="video" value="true" <?php if( isset( $contact_info[ "video" ] ) ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Survey</label>
      <input type="checkbox" name="survey" value="true" <?php if( isset( $contact_info[ "survey" ] ) ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label title="Dues Authorization Card" class="checkbox inline">DAC</label>
      <input type="checkbox" name="dac" value="true" <?php if( isset( $contact_info[ "dues_card" ] ) ) { echo( "checked" ); } ?>>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span4">
      <label class="checkbox inline">Potential Legal Issues</label>
      <input type="checkbox" name="pliCheck" id="pliCheck" value="true" <?php if( isset( $contact_info[ "has_legal_issues" ] ) ) { echo( "checked" ); } ?>>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span12">
      <textarea name="pliText" id="pliText"
        <?php if( isset( $contact_info[ "has_legal_issues" ] ) && $contact_info[ "legal_issues" ] != "" ) { ?>
          class="span12"
        <?php } else { ?>
          class="span12 hide"
        <?php } ?>
        placeholder="Type potential legal issues here"><?php echo( $contact_info[ 'legal_issues' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Organizer</div>
    <div class="span2">
      <select id="organizer" class="span12">
        <option></option>
        <?php
          $qs = "SELECT id, username
                 FROM users";
          $oqr = execute_query( $qs, $mc );
          
          while( $organizer = mysql_fetch_array( $oqr ) ) { ?>
            <option <?php if( $organizer[ "id" ] == $contact_info[ "cs_oid" ] ) { echo( "selected" ); } ?>>
              <?php echo( $organizer[ "username" ] ); ?>
            </option>
        <?php } ?>
      </select>
    </div>
    
    <?php $ct = $contact_info[ "cs_contact_type" ]; ?>
    
    <div class="span1">Contact Type</div>
    <div class="span4 inline contact-type">
      <label class="radio inline">House</label>
      <input type="radio" name="contactType" value="house" id="cth" <?php if( $ct == "house" || $ct == "" ) { echo( "checked" ); } ?>>
      <label class="radio inline">Worksite</label>
      <input type="radio" name="contactType" value="worksite" id="ctw" <?php if( $ct == "worksite" ) { echo( "checked" ); } ?>>
      <label class="radio inline">Other</label>
      <input type="radio" name="contactType" value="other" id="cto" <?php if( $ct != "" && $ct != "house" && $ct != "worksite" ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <input type="text" class="span12" name="ctoText" id="ctoText"
        <?php if( $ct != "" && $ct != "house" && $ct != "worksite" ) { ?>
          value="<?php echo( $contact_info[ 'cs_contact_type' ] ); ?>"
          style="opacity:1"
        <?php } else { ?>
          style="opacity:0"
        <?php } ?>
        placeholder="Other contact type">
    </div>
    
    <div class="span2">
      <span class="span4">Date</span>
      <input type="text" name="date" id="date" class="span8">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span6">Notes</div>
    <div class="span6">Things to improve on</div>
  </div>
 
  <div class="row-fluid">
    <div class="span6">
      <textarea name="notes" class="span12" placeholder="Type notes here"><?php echo( $contact_info[ 'cs_notes' ] ); ?></textarea>
    </div>
    
    <div class="span6">
      <textarea name="improvements" class="span12" placeholder="Type things to improve on here"><?php echo( $contact_info[ 'improvements' ] ); ?></textarea>
    </div>
  </div>
</div>
