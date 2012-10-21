<?php
/* File: load_phone_bank_list.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Handles generating a phone bank list for KC99 database.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

include( "common.php" );

/* Parse contact type */
$contactType = "";

if( $_GET[ 'workers' ] ) {
  $contactType = "contacts.contact_type = 'worker'";
}

if( $_GET[ 'students' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'student'";
  } else {
    $contactType .= " OR contacts.contact_type = 'student'";
  }
}

if( $_GET[ 'supporters' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'supporter'";
  } else {
    $contactType .= " OR contacts.contact_type = 'supporter'";
  }
}

if( $_GET[ 'organizers' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'organizer'";
  } else {
    $contactType .= " OR contacts.contact_type = 'organizer'";
  }
}

if( $_GET[ 'other' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 'other'";
  } else {
    $contactType .= " OR contacts.contact_type = 'other'";
  }
}

if( $contactType == "" ) { ?>
  <div class="alert alert-error">No groups selected.</div>
  <?php
  exit;
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "debrijjadb" ) or die( mysql_error() );
mysql_select_db( "kc99" );

/* Select desired information */
$qs = "SELECT contacts.first_name, contacts.last_name, contact_phone.phone, contact_phone.cell, workers.employer, students.school "
      . "FROM contacts "
      . "LEFT JOIN contact_phone ON contacts.id = contact_phone.cid "
      . "LEFT JOIN workers       ON contacts.id = workers.cid "
      . "LEFT JOIN students      ON contacts.id = students.cid "
      . "WHERE " . $contactType . " "
      . "ORDER BY contacts.last_name";
$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  echo( database_error_alert( mysql_error() ) );
  exit;
} ?>
          
<table class="table table-bordered table-striped table-condensed">
  <thead>
    <tr>
      <th>Last Name</th>
      <th>First Name</th>
      <?php if( $_GET[ 'workers' ] ) { ?>
        <th>Workplace</th>
      <?php } ?>
      <?php if( $_GET[ 'students' ] ) { ?>
        <th>School</th>
      <?php } ?>
      <th>Phone</th>
      <th>Cell</th>
      <th>Called</th>
      <th>Confirmed</th>
      <th>Notes</th>
    </tr>
  </thead>
  
  <tbody>
    <?php
      while( $contact_info = mysql_fetch_array( $qr ) ) { ?>
        <tr>
          <td width="120"><?php echo( ucwords( $contact_info[ 'last_name' ] ) ); ?></td>
          <td width="120"><?php echo( ucwords( $contact_info[ 'first_name' ] ) ); ?></td>
          <?php if( $_GET[ 'workers' ] ) { ?>
            <td width="150"><?php echo( ucwords( $contact_info[ 'employer' ] ) ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'students' ] ) { ?>
            <td width="60"><?php echo( strtoupper( $contact_info[ 'school' ] ) ); ?></td>
          <?php } ?>

          <td width="100">
            <?php if( $contact_info[ 'phone' ] != 0 ) {
              if( strlen( $contact_info[ 'phone' ] ) == 10 ) {
                /* Area code and phone number */
                echo( "(" . substr( $contact_info[ 'phone' ], 0, 3 ) . ") "
                      . substr( $contact_info[ 'phone' ], 3, 3 ) . "-"
                      . substr( $contact_info[ 'phone' ], 6 ) );
              } else {
                /* Only phone number */
                echo( substr( $contact_info[ 'phone' ], 0, 3 ) . "-"
                      . substr( $contact_info[ 'phone' ], 3 ) );
              }
            } ?>
          </td>

          <td width="100">
            <?php if( $contact_info[ 'cell' ] != 0 ) {
              if( strlen( $contact_info[ 'cell' ] ) == 10 ) {
                /* Area code and cell phone number */
                echo( "(" . substr( $contact_info[ 'cell' ], 0, 3 ) . ") "
                      . substr( $contact_info[ 'cell' ], 3, 3 ) . "-"
                      . substr( $contact_info[ 'cell' ], 6 ) );
              } else {
                /* Only cell phone number */
                echo( substr( $contact_info[ 'cell' ], 0, 3 ) . "-"
                      . substr( $contact_info[ 'cell' ], 3 ) );
              }
            } ?>
          </td>
          
          <td width="20"></td>
          <td width="50"></td>
          <td></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>