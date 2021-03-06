<?php
/* File: load_action_form.php
 * Author: Amr Gaber
 * Created: 2013/3/2
 * Description: Loads the action form for KC99 database.
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
  $ainfo = Array();
} else {
  $aid = mysql_real_escape_string( $_GET[ 'aid' ] );

  /* Get action information */
  $qs = "SELECT *
         FROM actions
         WHERE aid = " . $aid;
  
  $qr = execute_query( $qs, $mc );

  $ainfo = mysql_fetch_array( $qr );
} ?>

<div class="well">
  <div class="row-fluid">
    <div class="span1">Action Name</div>
    <div class="span8">
      <input type="text" name="aname" class="span12" id="aname"
             value="<?php echo( $ainfo[ 'aname' ] ); ?>"
             placeholder="Type action name here"
             required>
    </div>
  
  <?php if( $_GET[ 'aid' ] ) { ?>
    <div class="span2">Number of Contacts</div>
    <div class="span1">
      <?php $qs = "SELECT contact_action.*
                   FROM contact_action
                   WHERE aid = " . $aid;
    
      $aqr = execute_query( $qs, $mc ); ?>
      
      <input type="text" name="numContacts" class="span12"
           value="<?php echo( mysql_num_rows( $aqr ) ); ?>"
           placeholder="#" disabled>
    </div>
    <?php } ?>
  </div>
</div>

<div class="well">
  <div class="row-fluid">
    <div class="span1">Add Contact</div>

    <div class="span3">
        <input type="text" id="firstName" name="firstName" class="span12 search-query mobile-margin" placeholder="First Name">
    </div>
    
    <div class="span3">
        <input type="text" id="lastName" name="lastName" class="span12 search-query mobile-margin" placeholder="Last Name">
    </div>
    
    <button type="button" class="btn btn-info span1 mobile-margin" id="add-contact-search-button"><i class="icon-search"></i></button>
    <button type="button" class="btn span1 mobile-margin" id="add-contact-clear-button">Clear</button>
  </div>
</div>
  
<div class="row-fluid" id="add-contact-search-results">
</div>

<div class="row-fluid">
  <?php if( $_GET[ "aid" ] ) {
    $qs = "SELECT contact_action.cid,
                  contact_action.aid,
                  contact_action.date,
                  contacts.first_name,
                  contacts.last_name,
                  contacts.street_no,
                  contacts.apt_no,
                  contacts.city,
                  contacts.state,
                  contacts.zipcode,
                  workers.wid
           FROM contact_action 
             LEFT JOIN contacts ON contact_action.cid = contacts.id
             LEFT JOIN workers ON contact_action.cid = workers.cid
           WHERE contact_action.aid = " . $aid . "
           ORDER BY contacts.first_name, contacts.last_name";
    
    $aqr = execute_query( $qs, $mc );
  } ?>

  <h4>Contacts</h4>
  <hr>
  
  <table class="table table-bordered table-striped table-condensed" id="worker-table">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Shop</th>
        <th>Date Added</th>
        <th style="text-align: center;">Remove</th>
      </tr>
    </thead>
    
    <tbody id="contact-table-body">
      <?php if( mysql_num_rows( $aqr ) > 0 ) {
        while( $contacts = mysql_fetch_array( $aqr ) ) {
          /* get workplace information */
          if( strlen( $contacts[ 'wid' ] ) > 0 ) {
            $qs = "SELECT workplaces.wname,
                          workplaces.street_no
                   FROM workplaces
                   WHERE workplaces.wid = " . $contacts[ 'wid' ];

            $wqr = execute_query( $qs, $mc );
            $winfo = mysql_fetch_array( $wqr );
          } else {
            $winfo = Array();
          } ?>
          
          <tr class="contact" data-id="<?php echo( $contacts[ 'cid' ] ); ?>">
            <td><a href="view_contact.php?id=<?php echo( $contacts[ 'cid' ] ); ?>" target="_blank"><?php echo( $contacts[ "first_name" ] ); ?></a></td>
            <td><a href="view_contact.php?id=<?php echo( $contacts[ 'cid' ] ); ?>" target="_blank"><?php echo( $contacts[ "last_name" ] ); ?></a></td>
            <td><?php if( $contacts[ "apt_no" ] != "" && !is_null( $contacts[ "apt_no" ] ) ) {
                $apt_no = " #" . $contacts[ "apt_no" ];
              } else {
                $apt_no = "";
              }
              
              $addr = $contacts[ "street_no" ] . $apt_no . ", " . $contacts[ "city" ] . ", " . $contacts[ "state" ] . " " . $contacts[ "zipcode" ]; ?>

              <a href="https://maps.google.com/maps?q=<?php echo( $addr ); ?>" target="_blank">
                <?php echo( $addr ); ?>
              </a>
            </td>
            <td><a href="view_shop_profile.php?wid=<?php echo( $contacts[ 'wid' ] ); ?>" target="_blank"><?php echo( $winfo[ "wname" ] . " " . $winfo[ "street_no" ] ); ?></a></td>
            <td><?php echo( $contacts[ 'date' ] ); ?></td>
            <td style="text-align: center;"><button type="button" class="btn btn-small btn-danger" onclick="$( this ).parent().parent().remove();"><i class="icon-minus"></i></button></td>
          </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="row-fluid">
  <div id="action-form-status" class="alert alert-error hide">
  </div>
</div>

<div class="row-fluid">
  <?php if( $_GET[ 'add' ] ) { ?>
    <button type="submit" id="save-button" class="btn btn-primary btn-large">Add Action</button>
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
