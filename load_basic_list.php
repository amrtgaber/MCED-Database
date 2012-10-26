<?php
/* File: load_basic_list.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Handles generating a basic list for KC99 database.
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

/* Build query string to retrieve contact information */
$selection  = "contacts.id, contacts.first_name, contacts.last_name";
$joinString = "";

if( $_GET[ 'contactType' ] ) {
  $selection .= ", contacts.contact_type";
}

if( $_GET[ 'assessment' ] ) {
  $selection .= ", contacts.assessment";
}

if( $_GET[ 'address' ] ) {
  $selection .= ", contacts.street_no, contacts.apt_no, contacts.city, contacts.state, contacts.zipcode";
}

if( $_GET[ 'phoneNumber' ] ) {
  $selection  .= ", contact_phone.phone, contact_phone.cell";
  $joinString .= " LEFT JOIN contact_phone ON contacts.id = contact_phone.cid";
}

if( $_GET[ 'email' ] ) {
  $selection  .= ", contact_email.email";
  $joinString .= " LEFT JOIN contact_email ON contacts.id = contact_email.cid";
}

if( $_GET[ 'wage' ] || $_GET[ 'workplace' ] ) {
  $joinString .= " LEFT JOIN workers ON contacts.id = workers.cid";

  if( $_GET[ 'wage' ] ) {
    $selection .= ", workers.wage";
  }

  if( $_GET[ 'workplace' ] ) {
    $selection  .= ", workers.employer, workplaces.wname";
    $joinString .= " LEFT JOIN workplaces ON workers.wid = workplaces.wid";
  }
}

if( $_GET[ 'school' ] || $_GET[ 'schoolYear' ] ) {
  $joinString .= " LEFT JOIN students ON contacts.id = students.cid";

  if( $_GET[ 'school'] ) {
    $selection .= ", students.school";
  }

  if( $_GET[ 'schoolYear' ] ) {
    $selection .= ", students.syear";
  }
}

if( $_GET[ 'assignedOrganizer' ] ) {
  $selection  .= ", CONCAT( "
                 . "assigned_organizers.first_name, ' ', assigned_organizers.last_name ) "
                 . "AS assigned_organizer";

  $joinString .= " LEFT JOIN ( "
                 . "SELECT contact_organizer.cid, contacts.first_name, contacts.last_name "
                 . "FROM contacts, contact_organizer "
                 . "WHERE contacts.id = contact_organizer.oid "
                 . ") assigned_organizers ON contacts.id = assigned_organizers.cid";
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
mysql_select_db( "kc99_data" );

/* Select desired information */
$qs = "SELECT " . $selection . " "
      . "FROM contacts "
      . $joinString . " "
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
      
      <?php if( $_GET[ 'contactType' ] ) { ?>
        <th>Contact Type</th>
      <?php } ?>
      
      <?php if( $_GET[ 'assessment' ] ) { ?>
        <th>Assessment</th>
      <?php } ?>
      
      <?php if( $_GET[ 'address' ] ) { ?>
        <th>Address</th>
      <?php } ?>
      
      <?php if( $_GET[ 'phoneNumber' ] ) { ?>
        <th>Phone</th>
        <th>Cell</th>
      <?php } ?>
      
      <?php if( $_GET[ 'email' ] ) { ?>
        <th>Email</th>
      <?php } ?>
      
      <?php if( $_GET[ 'wage' ] ) { ?>
        <th>Wage</th>
      <?php } ?>
      
      <?php if( $_GET[ 'workplace' ] ) { ?>
        <th>Workplace</th>
      <?php } ?>
      
      <?php if( $_GET[ 'school' ] ) { ?>
        <th>School</th>
      <?php } ?>
      
      <?php if( $_GET[ 'schoolYear' ] ) { ?>
        <th>School Year</th>
      <?php } ?>
      
      <?php if( $_GET[ 'assignedOrganizer' ] ) { ?>
        <th>Assigned Organizer</th>
      <?php } ?>

      <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
        <th><i class="icon-pencil"></i></th>
      <?php } ?>

      <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
        <th><i class="icon-trash"></i></th>
      <?php } ?>
    </tr>
  </thead>
  
  <tbody>
    <?php
      while( $contact_info = mysql_fetch_array( $qr ) ) { ?>
        <tr>
          <td id="lastname<?php echo( $contact_info[ 'id' ] ); ?>"><?php echo( ucwords( $contact_info[ 'last_name' ] ) ); ?></td>
          <td id="firstname<?php echo( $contact_info[ 'id' ] ); ?>"><?php echo( ucwords( $contact_info[ 'first_name' ] ) ); ?></td>

          <?php if( $_GET[ 'contactType' ] ) { ?>
            <td><?php echo( $contact_info[ 'contact_type' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'assessment' ] ) { ?>
            <td><?php echo( $contact_info[ 'assessment' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'address' ] ) { 
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
          ?>
            <td><?php echo( $address ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'phoneNumber' ] ) { ?>
            <td>
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
           
            <td>
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
          <?php } ?>

          <?php if( $_GET[ 'email' ] ) { ?>
            <td><?php echo( $contact_info[ 'email' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'wage' ] ) { ?>
            <td><?php if( $contact_info[ 'wage' ] ) { echo( "$" ); } echo( $contact_info[ 'wage' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'workplace' ] ) { ?>
            <td><?php echo( ucwords( $contact_info[ 'employer' ] ) ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'school' ] ) { ?>
            <td><?php echo( strtoupper( $contact_info[ 'school' ] ) ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'schoolYear' ] ) { ?>
            <td>20<?php echo( $contact_info[ 'syear' ] ); ?></td>
          <?php } ?>

          <?php if( $_GET[ 'assignedOrganizer' ] ) { ?>
            <td><?php echo( ucwords( $contact_info[ 'assigned_organizer' ] ) ); ?></td>
          <?php } ?>

          <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
            <td width="20"><button type="button"
                                   data-id="<?php echo( $contact_info[ 'id' ] ); ?>"
                                   class="btn btn-small btn-info edit">
                                     <i class="icon-pencil"></i>
                           </button>
            </td>
          <?php  } ?>
  
          <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
            <td width="20"><button type="button"
                                   data-id="<?php echo( $contact_info[ 'id' ] ); ?>"
                                   class="btn btn-small btn-danger remove">
                                     <i class="icon-trash"></i>
                           </button>
            </td>
          <?php  } ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
