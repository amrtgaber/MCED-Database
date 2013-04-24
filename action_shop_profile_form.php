<?php
/* File: action_shop_profile_form.php
 * Author: Amr Gaber
 * Created: 2013/1/28
 * Description: Handles adding a shop profile for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  alert_error( "You must be logged in to add a shop profile." );
}

/* Must have privilege level of 1 or greater to add a shop profile */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  alert_error( "You do not have the required privilege level to add a shop profile." );
}

/* Check for required fields and validate form */

/* Check that workplace name exists */
if( !isset( $_POST[ 'wname' ] ) || $_POST[ 'wname' ] == "" ) {
  alert_error( "Workplace name is a required field." );
}

/* Check that address exists */
if( !isset( $_POST[ 'address' ] ) || $_POST[ 'address' ] == "" ) {
  alert_error( "Address is a required field." );
}

/* Check that city exists */
if( !isset( $_POST[ 'city' ] ) || $_POST[ 'city' ] == "" ) {
  alert_error( "City is a required field." );
}

/* Check that state exists */
if( !isset( $_POST[ 'state' ] ) || $_POST[ 'state' ] == "" ) {
  alert_error( "State is a required field." );
}

/* Ensure state is 2 letter abbr */
if( strlen( $_POST[ 'state' ] ) != 2 ) {
  alert_error( "State must be 2 letter abbreviation." );
}

/* Check that zipcode exists */
if( !isset( $_POST[ 'zipcode' ] ) || $_POST[ 'zipcode' ] == "" ) {
  alert_error( "Zipcode is a required field." );
}

/* Ensure zipcode is 5 digits */
if( !ctype_digit( $_POST[ 'zipcode' ] ) || strlen( $_POST[ 'zipcode' ] ) != 5 ) {
  alert_error( "Zipcode field is invalid." );
}

/* Check that phone exists */
if( !isset( $_POST[ 'phone' ] ) || $_POST[ 'phone' ] == "" ) {
  alert_error( "Phone is a required field." );
}

/* Ensure phone is 10 digits */
if( !ctype_digit( $_POST[ 'phone' ] ) || strlen( $_POST[ 'phone' ] ) != 10 ) {
  alert_error( "Phone field is invalid." );
}

/* If num workers exist ensure that it is a digit */
if( $_POST[ 'numWorkers' ] != "" && !ctype_digit( $_POST[ 'numWorkers' ] ) ) {
  alert_error( "Total Workers can only contain digits." );
}

/* connect to database */
$mc = connect_to_database();

/* If id is present, update existing shop profile. Otherwise insert new shop. */
if( $_POST[ 'id' ] ) {
  /* Must have privilege level of 2 or greater to modify a shop profile */
  if( $_SESSION[ 'privilege_level' ] < 2 ) {
    alert_error( "You do not have the required privilege level to modify a shop profile." );
  }

  $id = mysql_real_escape_string( $_POST[ 'id' ] );
  
  /* Update existing shop profile */
  $wname = mysql_real_escape_string( $_POST[ "wname" ] );
  
  $qs = "UPDATE workplaces
         SET wname = '" . $wname . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
  
  /* Get existing shop profile information */
  $qs = "SELECT workplaces.*
         FROM workplaces
         LEFT JOIN workers ON workplaces.wid = workers.cid
         WHERE workplaces.wid = " . $id;
  
  $qr = execute_query( $qs, $mc );
  
  $shop_info = mysql_fetch_array( $qr );
} else {
  /* Must have privilege level of 1 or greater to add a shop profile */
  if( $_SESSION[ 'privilege_level' ] < 1 ) {
    alert_error( "You do not have the required privilege level to add a shop profile." );
  }
  
  /* Insert new shop */
  $wname = mysql_real_escape_string( $_POST[ "wname" ] );
  
  $qs = "INSERT INTO workplaces
        ( wname )
        VALUES ( '" . $wname . "' )";

  $qr = execute_query( $qs, $mc );

  /* Get id of the shop that was just added */
  $qs = "SELECT wid
         FROM workplaces
         WHERE wname = '" . $wname . "'
         ORDER BY wid DESC
         LIMIT 1";
         
  $qr = execute_query( $qs, $mc );

  $shop_info = mysql_fetch_array( $qr );
  $id = $shop_info[ 'wid' ];
}

/* Insert/update values */

if( $_POST[ 'address' ] ) {
  $streetno = mysql_real_escape_string( $_POST[ 'address' ] );
  
  $qs = "UPDATE workplaces
         SET street_no = '" . $streetno . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );

} else {
  if( !is_null( $shop_info[ 'street_no' ] ) ) {
    $qs = "UPDATE workplaces
           SET street_no = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* City */
if( $_POST[ 'city' ] ) {
  $city = mysql_real_escape_string( $_POST[ 'city' ] );
  
  $qs = "UPDATE workplaces
         SET city = '" . $city . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'city' ] ) ) {
    $qs = "UPDATE workplaces
           SET city = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* State */
if( $_POST[ 'state' ] ) {
  $state = mysql_real_escape_string( strtoupper( $_POST[ 'state' ] ) );
  
  $qs = "UPDATE workplaces
         SET state = '" . $state . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'state' ] ) ) {
    $qs = "UPDATE workplaces
           SET state = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Zipcode */
if( $_POST[ 'zipcode' ] ) {
  $zipcode = mysql_real_escape_string( $_POST[ 'zipcode' ] );
  
  $qs = "UPDATE workplaces
         SET zipcode = " . $zipcode . "
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'zipcode' ] ) ) {
    $qs = "UPDATE workplaces
           SET zipcode = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Phone Number */
if( $_POST[ 'phone' ] ) {
  $phone = mysql_real_escape_string( $_POST[ 'phone' ] );
  
  $qs = "UPDATE workplaces
         SET phone = " . $phone . "
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'phone' ] ) ) {
    $qs = "UPDATE workplaces
           SET phone = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* CEO */
if( $_POST[ 'ceo' ] ) {
  $ceo = mysql_real_escape_string( $_POST[ 'ceo' ] );
  
  $qs = "UPDATE workplaces
         SET ceo = '" . $ceo . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'ceo' ] ) ) {
    $qs = "UPDATE workplaces
           SET ceo = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Parent Company */
if( $_POST[ 'parentCompany' ] ) {
  $parent_company = mysql_real_escape_string( $_POST[ 'parentCompany' ] );
  
  $qs = "UPDATE workplaces
         SET parent_company = '" . $parent_company . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'parentCompany' ] ) ) {
    $qs = "UPDATE workplaces
           SET parent_company = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Number of Workers */
if( $_POST[ 'numWorkers' ] ) {
  $num_workers = mysql_real_escape_string( $_POST[ 'numWorkers' ] );
  
  $qs = "UPDATE workplaces
         SET num_workers = " . $num_workers . "
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'numWorkers' ] ) ) {
    $qs = "UPDATE workplaces
           SET num_workers = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Notes */
if( $_POST[ 'notes' ] ) {
  $notes = mysql_real_escape_string( $_POST[ 'notes' ] );
  
  $qs = "UPDATE workplaces
         SET wnotes = '" . $notes . "'
         WHERE wid = " . $id;

  $qr = execute_query( $qs, $mc );
} else {
  if( !is_null( $shop_info[ 'wnotes' ] ) ) {
    $qs = "UPDATE workplaces
           SET wnotes = NULL
           WHERE wid = " . $id;

    $qr = execute_query( $qs, $mc );
  }
}

/* Add Workers to this shop */
if( $_POST[ "addWorkers" ] ) {
  $workerIDs = explode( ",", $_POST[ "addWorkers" ] );
  
  foreach( $workerIDs as $cid ) {
    $cid = mysql_real_escape_string( $cid );

    /* If cid exists don't insert */    
    $qs = "SELECT cid
           FROM workers
           WHERE cid = " . $cid . " AND wid = " . $id;
    
    $wqr = execute_query( $qs, $mc );
    
    if( mysql_num_rows( $wqr ) == 0 ) {
      $qs = "INSERT INTO workers
          ( cid, wid )
          VALUES ( " . $cid . "," . $id . " )";

      execute_query( $qs, $mc );
    }
  }
  
  /* delete removed workers */
  $qs = "SELECT cid
         FROM workers
         WHERE wid = " . $id;
  
  $wqr = execute_query( $qs, $mc );
  
  while( $winfo = mysql_fetch_array( $wqr ) ) {
    if( !in_array( $winfo[ "cid" ], $workerIDs ) ) {
      $qs = "DELETE
             FROM workers
             WHERE cid = " . $winfo[ "cid" ] . " AND wid = " . $id;

      execute_query( $qs, $mc );
    }
  }
} else {
  $qs = "DELETE
         FROM workers
         WHERE wid = " . $id;
  
  execute_query( $qs, $mc );
}

/* Return success */
if( $_POST[ 'id' ] ) { ?>
  <div class="alert alert-success">
    The workplace <?php echo( $wname );?> was successfully modified.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide();">OK</button>
  </div>
<?php } else { ?>
  <div class="alert alert-success">
    The workplace <?php echo( $wname );?> was successfully added to the database.
    <button type="button" class="btn btn-small btn-success" data-dismiss="modal" onclick="$( this ).parent().hide(); $( 'form' ).each(function () { this.reset(); }); $( '.worker' ).each(function() { $( this ).remove(); }); $( '#add-worker-table' ).remove();">OK</button>
  </div>
<?php } ?>
