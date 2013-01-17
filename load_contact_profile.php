<?php
/* File: load_contact_profile.php
 * Author: Amr Gaber
 * Created: 18/11/2012
 * Description: Returns the contact profile for KC99 database.
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
if( !isset( $_GET[ 'id' ] ) ) {
  alert_error( "No contact selected." );
}
  
$id = mysql_real_escape_string( $_GET[ 'id' ] );

/* Get contact information */
$qs = "SELECT contacts.*,
              contact_phone.*,
              contact_email.*,
              workers.*,
              students.*,
              CONCAT( assigned_organizers.first_name, ' ', assigned_organizers.last_name ) AS assigned_organizer
       FROM contacts
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid
       LEFT JOIN workplaces    ON workers.wid = workplaces.wid
       LEFT JOIN students      ON contacts.id = students.cid
       LEFT JOIN ( 
                 SELECT contact_organizer.cid, contacts.first_name, contacts.last_name
                 FROM contacts, contact_organizer
                 WHERE contacts.id = contact_organizer.oid
                 ) assigned_organizers ON contacts.id = assigned_organizers.cid
       WHERE contacts.id = " . $id;

$qr = execute_query( $qs, $mc );

$contact_info = mysql_fetch_array( $qr );

?>

<h2><?php echo( ucwords( $contact_info[ 'first_name' ] . " " . $contact_info[ 'last_name' ] ) ); ?></h2>

<table class="table table-hover">
  <tr>
    <td class="info-label">Makes less than $10 an hour</td>
    <td><?php if( isset( $contact_info[ 'wage_below_10' ] ) ) { echo( "Yes" ); } else { echo( "No" ); } ?></td>
  </tr>

  <tr>
    <td class="info-label">Employer</td>
    <td><?php echo( ucwords( $contact_info[ 'employer' ] ) ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Address</td>
    <td><?php
      if( $contact_info[ 'street_no' ] ) {
        $address = ucwords( $contact_info[ 'street_no' ] );
        
        if( $contact_info[ 'apt_no' ] ) {
          $address .= " Apt. " . strtoupper( $contact_info[ 'apt_no' ] );
        }

        $address .= ", "
          . ucwords( $contact_info[ 'city' ] )
          . ", "
          . strtoupper( $contact_info[ 'state' ] )
          . " "
          . $contact_info[ 'zipcode' ];
      } else {
        $address = "";
      }
      
      echo( $address ); ?>
    </td>
  </tr>

  <tr>
    <td class="info-label">Phone</td>
    <td><?php
      if( $contact_info[ 'phone' ] != 0 ) {
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
  </tr>

  <tr>
    <td class="info-label">Cell</td>
    <td><?php
      if( $contact_info[ 'cell' ] != 0 ) {
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
  </tr>

  <tr>
    <td class="info-label">Send text updates</td>
    <td>
       <?php
         if( isset( $contact_info[ 'text_updates' ] ) ) {
           echo( "Yes" );
         } else {
           echo( "No" );
         }
       ?>
    </td>
  </tr>

  <tr>
    <td class="info-label">Email</td>
    <td><?php echo( $contact_info[ 'email' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Wage</td>
    <td>$<?php echo( $contact_info[ 'wage' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">School</td>
    <td><?php echo( strtoupper( $contact_info[ 'school' ] ) ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Expected graduation year</td>
    <td><?php echo( $contact_info[ 'syear' ] ); ?></td>
  </tr>

  <tr>
    <td class="info-label">Contact Type</td>
    <td>
      <?php $contact_type = $contact_info[ "contact_type" ];
      
        if( $contact_type == 1 ) {
          echo( "Worker" );
        } else if ( $contact_type == 2 ) {
          echo( "Student" );
        } else if ( $contact_type == 3 ) {
          echo( "Supporter" );
        } else {
          echo( "Other" );
        }
      ?>
    </td>
  </tr>
  
  <tr>
    <td class="info-label">Contact Sheets</td>
    <td>
      <?php
        $qs = "SELECT id,
                      cs_date
               FROM contact_sheet
               WHERE contact_sheet.cid = " . $id . "
               ORDER BY cs_date DESC";
               
        $csqr = execute_query( $qs, $mc );
        
        if( mysql_num_rows( $csqr ) > 0 ) {
          while( $cs_info = mysql_fetch_array( $csqr ) ) { ?>
            <a href="view_contact_sheet.php?id=<?php echo( $cs_info[ 'id' ] ); ?>" target="_blank"><?php echo( $cs_info[ "cs_date" ] ); ?></a>
            <br>
          <?php }
        }
      ?>
    </td>
  </tr>
</table>
