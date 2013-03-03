<?php
/* File: load_shop_profile_form.php
 * Author: Amr Gaber
 * Created: 2013/1/26
 * Description: Returns the shop profile form for KC99 database.
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

/* If id is present, populate form for modification. */
if( $_GET[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify a contact */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to modify a shop profile." );
  }

  $id = mysql_real_escape_string( $_GET[ 'id' ] );

  /* Get shop information */
  $qs = "SELECT workplaces.*,
                workers.*
         FROM workplaces
         LEFT JOIN workers ON workplaces.wid = workers.wid
         WHERE workplaces.wid = " . $id;
  
  $qr = execute_query( $qs, $mc );

  $workplace_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a shop profile */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add a shop profile." );
  }

  $workplace_info = Array();
}

?>

<div class="well"> 
  <div class="row-fluid">
    <div class="span2">Workplace Name</div>
    <div class="span10">
      <input type="text" name="wname" class="span12"
             value="<?php echo( $workplace_info[ 'wname' ] ); ?>"
             placeholder="Type workplace name here">
    </div>
  </div>

  <div class="row-fluid">
    <div class="span1">Address</div>
    <div class="span3">
      <input type="text" name="address" class="span12"
             value="<?php echo( $workplace_info[ 'street_no' ] ); ?>"
             placeholder="Type address here">
    </div>

    <div class="span1">City</div>
    <div class="span2">
      <input type="text" name="city" class="span12"
             value="<?php echo( $workplace_info[ 'city' ] ); ?>"
             placeholder="Type city here">
    </div>

    <div class="span1">State</div>
    <div class="span1">
      <input type="text" name="state" class="span12"
             value="<?php echo( $workplace_info[ 'state' ] ); ?>"
             placeholder="State">
    </div>
    <div class="span1">Zipcode</div>
    <div class="span2">
      <input type="text" name="zipcode" class="span12"
             value="<?php echo( $workplace_info[ 'zipcode' ] ); ?>"
             placeholder="Type zipcode here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">Phone</div>
    <div class="span5">
      <input type="text" name="phone" class="span12"
             value="<?php echo( $workplace_info[ 'phone' ] ); ?>"
             placeholder="Type phone number here">
    </div>
  </div>
    
  <div class="row-fluid">
    <div class="span1">CEO</div>
    <div class="span4">
      <input type="text" name="ceo" class="span12"
             value="<?php echo( $workplace_info[ 'ceo' ] ); ?>"
             placeholder="Type CEO's name here">
    </div>
    
    <div class="span2">Parent Company</div>
    <div class="span5">
      <input type="text" name="parentCompany" class="span12"
             value="<?php echo( $workplace_info[ 'parent_company' ] ); ?>"
             placeholder="Type parent company here">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Total Workers</div>
    <div class="span1">
      <input type="text" name="numWorkers" class="span12"
             value="<?php echo( $workplace_info[ 'num_workers' ] ); ?>"
             placeholder="#">
    </div>
  </div>
  
  <div class="row-fluid">
    <div class="span1">Add Worker</div>
    <div class="span9">
      <input type="text" class="span12" id="addWorker" name="addWorker" placeholder="Type worker name here" data-provide="typeahead" data-items="50" data-source="[
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
             ]">
    </div>
    <div class="span2">
      <button type="button" id="add-worker-button" class="btn btn-info">Add Worker</button>
    </div>
  </div>
  
  <br>
  
  <div class="row-fluid" id="added-workers">
    <?php
      if( $_GET[ "id" ] ) {
      $qs = "SELECT id,
                    first_name,
                    last_name,
                    street_no
             FROM workers
             LEFT JOIN contacts ON workers.cid = contacts.id
             WHERE wid = " . $id;
      
      $wqr = execute_query( $qs, $mc );
      
      while( $winfo = mysql_fetch_array( $wqr ) ) { ?>
        <div class="row-fluid worker" data-id="<?php echo( $winfo[ 'id' ] ); ?>">
          <?php echo( $winfo[ "first_name" ] . " " . $winfo[ "last_name" ] . " | " . $winfo[ "street_no" ] ); ?>
          <button type='button' class='close' onclick='$( this ).parent().remove();'>&times;</button>
        </div>
      <?php }
    } ?>
  </div>
</div>
