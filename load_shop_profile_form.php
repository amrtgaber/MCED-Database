<?php
/* File: load_shop_profile_form.php
 * Author: Amr Gaber
 * Created: 2013/1/26
 * Description: Returns the shop profile form for KC99 database.
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
  $winfo = Array();
} else {
  $wid = mysql_real_escape_string( $_GET[ 'wid' ] );

  /* Get shop information */
  $qs = "SELECT workplaces.*
         FROM workplaces
         WHERE workplaces.wid = " . $wid;
  
  $qr = execute_query( $qs, $mc );

  $winfo = mysql_fetch_array( $qr );
} ?>

<div class="well"> 
  <div class="row-fluid">
    <div class="span2">Workplace Name</div>
    <div class="span10">
      <input type="text" name="wname" class="span12"
             value="<?php echo( $winfo[ 'wname' ] ); ?>"
             placeholder="Type workplace name here">
    </div>
  </div>

  <div class="row-fluid">
    <div class="span1">Address</div>
    <div class="span3">
      <input type="text" name="address" class="span12"
             value="<?php echo( $winfo[ 'street_no' ] ); ?>"
             placeholder="Type address here">
    </div>

    <div class="span1">City</div>
    <div class="span2">
      <input type="text" name="city" class="span12"
             value="<?php echo( $winfo[ 'city' ] ); ?>"
             placeholder="Type city here">
    </div>

    <div class="span1">State</div>
    <div class="span1">
      <input type="text" name="state" class="span12"
             value="<?php echo( $winfo[ 'state' ] ); ?>"
             placeholder="State">
    </div>
    <div class="span1">Zipcode</div>
    <div class="span2">
      <input type="text" name="zipcode" class="span12"
             value="<?php echo( $winfo[ 'zipcode' ] ); ?>"
             placeholder="Type zipcode here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">Phone</div>
    <div class="span5">
      <input type="text" name="phone" class="span12"
             value="<?php echo( $winfo[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">CEO</div>
    <div class="span4">
      <input type="text" name="ceo" class="span12"
             value="<?php echo( $winfo[ 'ceo' ] ); ?>"
             placeholder="Type CEO's name here">
    </div>
    
    <div class="span2">Parent Company</div>
    <div class="span5">
      <input type="text" name="parentCompany" class="span12"
             value="<?php echo( $winfo[ 'parent_company' ] ); ?>"
             placeholder="Type parent company here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Total Workers</div>
    <div class="span4">
      <input type="text" name="numWorkers" class="span2"
             value="<?php echo( $winfo[ 'num_workers' ] ); ?>"
             placeholder="#">
    </div>
    
    <?php if( $_GET[ 'id' ] ) { ?>
      <div class="span1">Contacted Workers</div>
      <div class="span5">
        <?php $qs = "SELECT workers.*
                     FROM workers
                     WHERE wid = " . $wid;
      
        $wqr = execute_query( $qs, $mc ); ?>
        
        <input type="text" name="numContactedWorkers" class="span2"
             value="<?php echo( mysql_num_rows( $wqr ) ); ?>"
             placeholder="#" disabled>
      </div>
    <?php } ?>
  </div>
  
  <br>
  
  <div class="row-fluid">
    <div class="span1">Notes</div>
    <div class="span11">
      <textarea name="notes" class="span12" placeholder="Type notes here"><?php echo( $winfo[ 'wnotes' ] ); ?></textarea>
    </div>
  </div>
</div>

<div class="well">
  <div class="row-fluid">
    <div class="span1">Add Worker</div>

    <div class="span3">
        <input type="text" id="firstName" class="span12 search-query mobile-search" placeholder="First Name">
    </div>
    
    <div class="span3">
        <input type="text" id="lastName" class="span12 search-query mobile-search" placeholder="Last Name">
    </div>
    
    <button type="button" class="btn btn-info span1 mobile-search" id="add-worker-search-button"><i class="icon-search"></i></button>
    <button type="button" class="btn span1 mobile-search" id="add-worker-clear-button">Clear</button>
  </div>
</div>
  
<div class="row-fluid" id="add-worker-search-results">
</div>

<div class="row-fluid">
  <?php if( $_GET[ "wid" ] ) {
    $qs = "SELECT workers.cid,
                  workers.wid,
                  contacts.first_name,
                  contacts.last_name,
                  contacts.street_no,
                  contacts.apt_no,
                  contacts.city,
                  contacts.state,
                  contacts.zipcode,
                  contact_sheet.job,
                  contact_sheet.rating,
                  MAX( contact_action.aid ) AS aid
           FROM workers 
             LEFT JOIN contacts       ON workers.cid = contacts.id
             LEFT JOIN contact_sheet  ON workers.cid = contact_sheet.cid
             LEFT JOIN contact_action ON workers.cid = contact_action.cid
           WHERE workers.wid = " . $wid . "
           GROUP BY workers.cid
           ORDER BY contacts.last_name";
    
    $wqr = execute_query( $qs, $mc );
  } ?>
    
  <h4>Workers</h4>
  <hr>
  
  <table class="table table-bordered table-striped table-condensed" id="worker-table">
    <thead>
      <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Address</th>
        <th>Job</th>
        <th style="text-align: center;">Rating</th>
        <th style="text-align: center;">L&A</th>
        <th style="text-align: center;">Remove</th>
      </tr>
    </thead>
    
    <tbody id="worker-table-body">
      <?php if( mysql_num_rows( $wqr ) > 0 ) {
        while( $workers = mysql_fetch_array( $wqr ) ) { ?>
          <tr class="worker" data-id="<?php echo( $workers[ 'cid' ] ); ?>">
            <td><a href="view_contact.php?id=<?php echo( $workers[ 'cid' ] ); ?>" target="_blank"><?php echo( $workers[ "last_name" ] ); ?></a></td>
            <td><a href="view_contact.php?id=<?php echo( $workers[ 'cid' ] ); ?>" target="_blank"><?php echo( $workers[ "first_name" ] ); ?></a></td>
            <td><?php if( $workers[ "apt_no" ] != "" && !is_null( $workers[ "apt_no" ] ) ) {
                $apt_no = " #" . $workers[ "apt_no" ];
              } else {
                $apt_no = "";
              }
            
              echo( $workers[ "street_no" ] . $apt_no . ", " . $workers[ "city" ] . ", " . $workers[ "state" ] . " " . $workers[ "zipcode" ] ); ?>
            </td>
            <td><?php echo( $workers[ "job" ] ); ?></td>
            <td style="text-align: center;"><?php echo( $workers[ "rating" ] ); ?></td>
            <td style="text-align: center;"><i class="<?php if( $workers[ 'aid' ] == 1003 ) { echo( 'icon-star' ); } ?>"></i></td>
            <td style="text-align: center;"><button type="button" class="btn btn-small btn-danger" onclick="$( this ).parent().parent().remove();"><i class="icon-minus"></i></button></td>
          </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="row-fluid">
  <div id="shop-profile-form-status" class="alert alert-error hide">
  </div>
</div>

<div class="row-fluid">
  <?php if( $_GET[ 'add' ] ) { ?>
    <button type="submit" id="save-button" class="btn btn-primary btn-large">Add Shop</button>
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
    This action cannot be undone. Clicking delete will permanently remove this shop profile from the database.
  </div>

  <div class="modal-footer">
    <button type="button" id="delete-confirm-button" class="btn btn-primary btn-danger">Delete</button>
    <button type="button" class="btn btn-inverse" data-dismiss="modal">Cancel</button>
  </div>
</div>
