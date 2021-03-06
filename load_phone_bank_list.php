<?php
/* File: load_phone_bank_list.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Handles generating a phone bank list for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* Parse contact type */
$contactType = "";

if( $_GET[ 'workers' ] ) {
  $contactType = "contacts.contact_type = 1";
}

if( $_GET[ 'students' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 2";
  } else {
    $contactType .= " OR contacts.contact_type = 2";
  }
}

if( $_GET[ 'supporters' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 3";
  } else {
    $contactType .= " OR contacts.contact_type = 3";
  }
}

if( $_GET[ 'other' ] ) {
  if( $contactType == "" ) {
    $contactType = "contacts.contact_type = 0";
  } else {
    $contactType .= " OR contacts.contact_type = 0";
  }
}

if( $contactType == "" ) {
  alert_error( "No groups selected." );
}

/* Connect to database */
$mc = connect_to_database();

/* Select desired information */
$qs = "SELECT contacts.first_name, contacts.last_name, contact_phone.phone, contact_phone.cell, workplaces.wname, students.school "
      . "FROM contacts "
      . "LEFT JOIN contact_phone ON contacts.id = contact_phone.cid "
      . "LEFT JOIN workers       ON contacts.id = workers.cid "
      . "LEFT JOIN workplaces    ON workers.wid = workplaces.wid"
      . "LEFT JOIN students      ON contacts.id = students.cid "
      . "WHERE " . $contactType . " "
      . "ORDER BY contacts.last_name";

$qr = execute_query( $qs, $mc );

if( $_GET[ 'print' ] ) { ?>
  <link href="css/load_phone_bank_list.css" rel="stylesheet">
<?php } ?>

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
      while( $contact_info = mysql_fetch_array( $qr ) ) {
        if( $contact_info[ 'phone' ] == 0 && $contact_info[ 'cell' ] == 0 ) {
          continue;
        } ?>
        <tr>
          <td width="120"><?php echo( $contact_info[ 'last_name' ] ); ?></td>
          <td width="120"><?php echo( $contact_info[ 'first_name' ] ); ?></td>
          <?php if( $_GET[ 'workers' ] ) { ?>
            <td width="150"><?php echo( $contact_info[ 'wname' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'students' ] ) { ?>
            <td width="60"><?php echo( $contact_info[ 'school' ] ); ?></td>
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
          <td width="500"></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
		
