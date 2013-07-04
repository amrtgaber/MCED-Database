<?php
/* File: load_contact_form.php
 * Author: Amr Gaber
 * Created: 02/10/2012
 * Description: Returns the contact add/update form for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* Connect to database */
$mc = connect_to_database();

if( $_GET[ 'add' ] ) {
  $cinfo = Array();
} else {
  $id = mysql_real_escape_string( $_GET[ 'id' ] );

  /* Get contact information */
  $qs = "SELECT contacts.*,
                contact_email.*,
                workers.*,
                students.*,
                contact_action.*
         FROM contacts
           LEFT JOIN contact_email  ON contacts.id = contact_email.cid
           LEFT JOIN workers        ON contacts.id = workers.cid
           LEFT JOIN students       ON contacts.id = students.cid
           LEFT JOIN contact_action ON contacts.id = contact_action.cid
         WHERE contacts.id = " . $id;
  
  $qr = execute_query( $qs, $mc );

  $cinfo = mysql_fetch_array( $qr );
} ?>

<div class="well" id="main"> 
  <div class="row-fluid">
    <div class="span1">First Name</div>
    <div class="span5">
      <input type="text" name="firstName" class="span12"
             value="<?php echo( $cinfo[ 'first_name' ] ); ?>"
             placeholder="Type first name here">
    </div>
    
    <div class="span1">Last Name</div>
    <div class="span5">
      <input type="text" name="lastName" class="span12"
             value="<?php echo( $cinfo[ 'last_name' ] ); ?>"
             placeholder="Type last name here">
    </div>
  </div>

  <div class="row-fluid">
    <div class="span1">Address</div>
    <div class="span8">
      <input type="text" name="address" class="span12"
             value="<?php echo( $cinfo[ 'street_no' ] ); ?>"
             placeholder="Type address here">
    </div>

    <div class="span1">Apt. no.</div>
    <div class="span2">
      <input type="text" name="aptNo" class="span12"
             value="<?php echo( $cinfo[ 'apt_no' ] ); ?>"
             placeholder="Type Apt. no. here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">City</div>
    <div class="span6">
      <input type="text" name="city" class="span12"
             value="<?php echo( $cinfo[ 'city' ] ); ?>"
             placeholder="Type city here">
    </div>

    <div class="span1">State</div>
    <div class="span1">
      <input type="text" name="state" class="span12"
             value="<?php echo( $cinfo[ 'state' ] ); ?>"
             placeholder="State">
    </div>
    <div class="span1">Zipcode</div>
    <div class="span2">
      <input type="text" name="zipcode" class="span12"
             value="<?php echo( $cinfo[ 'zipcode' ] ); ?>"
             placeholder="Type zipcode here">
    </div>
  </div>

  <div class="row-fluid">
    <?php if( $_GET[ "id" ] ) {
      $qs = "SELECT contact_phone.*
             FROM contact_phone
             WHERE cid = " . $cinfo[ 'id' ] . " AND main = 1";
             
      $pqr = execute_query( $qs, $mc );
      
      $pinfo1 = mysql_fetch_array( $pqr );
    } else {
      $pinfo1 = Array();
    } ?>
    
    <div class="span1">Phone</div>
    <div class="span3">
      <input type="text" name="phone1" class="span12"
             value="<?php echo( $pinfo1[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Cell</label>
      <input type="checkbox" name="cell1" value="true" <?php if( ord( $pinfo1[ 'cell' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Text</label>
      <input type="checkbox" name="text1" value="true" <?php if( ord( $pinfo1[ 'text_updates' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Email</div>
    <div class="span5">
      <input type="text" name="email" class="span12"
             value="<?php echo( $cinfo[ 'email' ] ); ?>"
             placeholder="Type email here">
    </div>
  </div>
  
  <div class="row-fluid">
    <h5>Workplaces</h5>
  </div>
  
  <div class="row-fluid">
    <?php if( $_GET[ "id" ] ) {
      $qs = "SELECT workers.wid,
                    workers.wage,
                    workplaces.wname
             FROM workers
               LEFT JOIN workplaces ON workers.wid = workplaces.wid
             WHERE workers.cid = " . $id . "
             ORDER BY workplaces.wname";
     
       $cwqr = execute_query( $qs, $mc );
       
       while( $winfo = mysql_fetch_array( $cwqr ) ) { ?>
        <div class="row-fluid">
          <div class="span1"></div>
          <div class="span5">
            <select id="workplace" class="span12 workplace-select">
              <option>&lt; select a workplace &gt;</option>
              <?php
                $qs = "SELECT wid,
                              wname,
                              street_no
                       FROM workplaces
                       ORDER BY wname";
                $wqr = execute_query( $qs, $mc );
                
                while( $workplace = mysql_fetch_array( $wqr ) ) { ?>
                  <option data-wid="<?php echo( $workplace[ 'wid' ] ); ?>" <?php if( $workplace[ "wid" ] == $winfo[ "wid" ] ) { echo( "selected" ); $wid = $winfo[ "wid" ]; $wage = $winfo[ "wage" ]; } ?>>
                    <?php echo( $workplace[ "wname" ] . " " . $workplace[ "street_no" ] ); ?>
                  </option>
              <?php } ?>
            </select>
          </div>
          
          <div class="span1">Wage</div>
          <div class="span1 input-prepend">
            <span class="add-on">$</span><input type="text" class="span12 wage" data-wid="<?php echo( $wid ); ?>" value="<?php echo( $wage ); ?>">
          </div>
        </div>
      <?php } ?>
    <?php } ?>
    
    <div class="row-fluid">
      <div class="span1"></div>
      <div class="span5">
        <select id="workplace" class="span12 workplace-select" data-last="true">
          <option>&lt; select a workplace &gt;</option>
          <?php
            $qs = "SELECT wid,
                          wname,
                          street_no
                   FROM workplaces
                   ORDER BY wname";
            $wqr = execute_query( $qs, $mc );
            
            while( $workplace = mysql_fetch_array( $wqr ) ) { ?>
              <option data-wid="<?php echo( $workplace[ 'wid' ] ); ?>">
                <?php echo( $workplace[ "wname" ] . " " . $workplace[ "street_no" ] ); ?>
              </option>
          <?php } ?>
        </select>
      </div>
      
      <div class="span1">Wage</div>
      <div class="span1 input-prepend">
        <span class="add-on">$</span><input type="text" class="span12 wage" data-wid="">
      </div>
    </div>
  </div>

  <div class="row-fluid">
    <h5>Actions</h5>
  </div>
  
  <?php if( $_GET[ "id" ] ) {
    $qs = "SELECT aid
           FROM contact_action
           WHERE cid = " . $id;
   
     $caqr = execute_query( $qs, $mc );
     
     while( $cainfo = mysql_fetch_array( $caqr ) ) { ?>
      <div class="row-fluid">
        <div class="span1"></div>
        <div class="span5">
          <select id="action" class="span12 action-select">
            <option>&lt; select an action &gt;</option>
            <?php
              $qs = "SELECT aid,
                            aname
                     FROM actions
                     ORDER BY aname";
              $aqr = execute_query( $qs, $mc );
              
              while( $action = mysql_fetch_array( $aqr ) ) { ?>
                <option data-aid="<?php echo( $action[ 'aid' ] ); ?>" <?php if( $action[ "aid" ] == $cainfo[ "aid" ] ) { echo( "selected" ); } ?>>
                  <?php echo( $action[ "aname" ] ); ?>
                </option>
            <?php } ?>
          </select>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
  
  <div class="row-fluid">
    <div class="span1"></div>
    <div class="span5">
      <select id="action" class="span12 action-select" data-last="true">
        <option>&lt; select an action &gt;</option>
        <?php
          $qs = "SELECT aid,
                        aname
                 FROM actions
                 ORDER BY aname";
          $aqr = execute_query( $qs, $mc );
          
          while( $action = mysql_fetch_array( $aqr ) ) { ?>
            <option data-aid="<?php echo( $action[ 'aid' ] ); ?>">
              <?php echo( $action[ "aname" ] ); ?>
            </option>
        <?php } ?>
      </select>
    </div>
  </div>
</div>

<div class="well">
  <?php if( $_GET[ "id" ] ) {
    $qs = "SELECT contact_phone.*
           FROM contact_phone
           WHERE cid = " . $cinfo[ 'id' ] . " AND main = 0";
           
    $pqr = execute_query( $qs, $mc );
    
    $pinfo2 = mysql_fetch_array( $pqr );
    $pinfo3 = mysql_fetch_array( $pqr );
    $pinfo4 = mysql_fetch_array( $pqr );
  } ?>
  
  <div class="row-fluid">
    <div class="span1">Phone 2</div>
    <div class="span3">
      <input type="text" name="phone2" class="span12"
             value="<?php echo( $pinfo2[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Cell</label>
      <input type="checkbox" name="cell2" value="true" <?php if( ord( $pinfo2[ 'cell' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Text</label>
      <input type="checkbox" name="text2" value="true" <?php if( ord( $pinfo2[ 'text_updates' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">Phone 3</div>
    <div class="span3">
      <input type="text" name="phone3" class="span12"
             value="<?php echo( $pinfo3[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Cell</label>
      <input type="checkbox" name="cell3" value="true" <?php if( ord( $pinfo3[ 'cell' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Text</label>
      <input type="checkbox" name="text3" value="true" <?php if( ord( $pinfo3[ 'text_updates' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Phone 4</div>
    <div class="span3">
      <input type="text" name="phone4" class="span12"
             value="<?php echo( $pinfo4[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Cell</label>
      <input type="checkbox" name="cell4" value="true" <?php if( ord( $pinfo4[ 'cell' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
    
    <div class="span2">
      <label class="checkbox inline">Text</label>
      <input type="checkbox" name="text4" value="true" <?php if( ord( $pinfo4[ 'text_updates' ] ) == 1 ) { echo( "checked" ); } ?>>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">School</div>
    <div class="span4">
      <input type="text" name="school" class="span12"
             value="<?php echo( $cinfo[ 'school' ] ); ?>"
             placeholder="Type school here">
    </div>

    <div class="span1">Year</div>
    <div class="span1 input-prepend">
      <span class="add-on">20</span><input type="text" name="syear" class="span12"
            value="<?php echo( $cinfo[ 'syear' ] ); ?>"
            placeholder="year">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Notes</div>
    <div class="span11">
      <textarea name="notes" class="span12" placeholder="Type notes here"><?php echo( $cinfo[ 'notes' ] ); ?></textarea>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">WIT Organizer</div>
    <div class="span3">
      <select id="wit-organizer" class="span12">
        <option>&lt; select an organizer &gt;</option>
        <?php
          $qs = "SELECT id, username
                 FROM users
                 ORDER BY username";
          $oqr = execute_query( $qs, $mc );
          
          while( $organizer = mysql_fetch_array( $oqr ) ) { ?>
            <option data-oid="<?php echo( $organizer[ 'id' ] ); ?>" <?php if( $organizer[ 'id' ] == $cinfo[ 'wit_oid' ] ) { echo( "selected" ); } ?>>
              <?php echo( $organizer[ "username" ] ); ?>
            </option>
        <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Contact Type</div>    
    <div class="span2">
      <select id="contactType" class="span12">
        <?php $contact_type = $cinfo[ "contact_type" ]; if( !$_GET[ 'id' ] ) { $contact_type = 1; } ?>
        <option data-ctype="1" <?php if( $contact_type == 1 ) { echo( "selected" ); } ?>>Worker</option>
        <option data-ctype="2" <?php if( $contact_type == 2 ) { echo( "selected" ); } ?>>Student</option>
        <option data-ctype="3" <?php if( $contact_type == 3 ) { echo( "selected" ); } ?>>Supporter</option>
        <option data-ctype="0" <?php if( $contact_type == 0 ) { echo( "selected" ); } ?>>Other</option>
      </select>
    </div>
    
    <div class="span2">
      <span class="span4">Date</span>
      <input type="text" name="date" id="date" class="span8" value="<?php echo( $cinfo[ 'cdate' ] ); ?>">
    </div>
  </div>
</div>

<div class="row-fluid">
  <div id="contact-form-status" class="alert alert-error hide">
  </div>
</div>

<div class="row-fluid">
  <?php if( $_GET[ 'add' ] ) { ?>
    <button type="submit" id="save-button" class="btn btn-primary btn-large">Add Contact</button>
  <?php } else { ?>
    <button type="submit" id="save-button" class="btn btn-primary btn-large">Save Changes</button>
  <?php } ?>
  
  <button type="button" id="cancel-button" class="btn btn-inverse btn-large">Cancel</button>
  
  <?php if( !$_GET[ 'add' ] ) { ?>
    <button type="button" id="delete-button" class="btn btn-danger btn-large pull-right" data-toggle="modal" data-target="#delete-modal">Delete</button>
  <?php } ?>
</div>

<div id="delete-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Are you sure?</h3>
  </div>
    
  <div class="modal-body">
    This action cannot be undone. Clicking delete will permanently remove this contact from the database.
  </div>

  <div class="modal-footer">
    <button type="button" id="delete-confirm-button" class="btn btn-primary btn-danger">Delete</button>
    <button type="button" class="btn btn-inverse" data-dismiss="modal">Cancel</button>
  </div>
</div>
