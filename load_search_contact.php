<?php
/* File: load_search_contact.php
 * Author: Amr Gaber
 * Created: 14/10/2012
 * Description: Displays a table of contacts selectable by a radio button.
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

/* map action ids to names */
$qs = "SELECT actions.aid,
              actions.aname
       FROM actions";
       
$qr = execute_query( $qs, $mc );
       
$anames = array();

while( $ainfo = mysql_fetch_array( $qr ) ) {
  $anames[ $ainfo[ 'aid' ] ] = $ainfo[ 'aname' ];
}

/* Get contact info */
$qs = "SELECT contacts.*,
              contact_phone.*,
              contact_email.email,
              workplaces.wid,
              workplaces.wname,
              workplaces.street_no AS wstreet_no,
              workers.wage,
              students.school
       FROM contacts
       LEFT JOIN contact_phone ON contacts.id = contact_phone.cid AND main = 1
       LEFT JOIN contact_email ON contacts.id = contact_email.cid
       LEFT JOIN workers       ON contacts.id = workers.cid
       LEFT JOIN workplaces    ON workers.wid = workplaces.wid
       LEFT JOIN students      ON contacts.id = students.cid
       GROUP BY contacts.id
       ORDER BY contacts.first_name, contacts.last_name";

$qr = execute_query( $qs, $mc );?>

<table class="table table-bordered table-striped table-condensed" id="contact-table">
  <thead>
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Address</th>
      <th>Phone</th>
      <th>Employer</th>
      <th>Wage</th>
      <th>Actions</th>
      <th>Email</th>
      <th>School</th>
      <th>BOR Date</th>
    </tr>
  </thead>
  
  <tbody>
    <?php while( $cinfo = mysql_fetch_array( $qr ) ) { ?>
      <tr>
        <td><a href="view_contact.php?id=<?php echo( $cinfo[ 'id' ] ); ?>" class="contact"><?php echo( $cinfo[ 'first_name' ] ); ?></a></td>
        <td><a href="view_contact.php?id=<?php echo( $cinfo[ 'id' ] ); ?>" class="contact"><?php echo( $cinfo[ 'last_name' ] ); ?></a></td>
        <td>
          <?php if( $cinfo[ "apt_no" ] != "" && !is_null( $cinfo[ "apt_no" ] ) ) {
            $apt_no = " #" . $cinfo[ "apt_no" ];
          } else {
            $apt_no = "";
          }
          
          $addr = $cinfo[ 'street_no' ] . $apt_no . ', ' . $cinfo[ 'city' ] . ', ' . $cinfo[ 'state' ] . ' ' . $cinfo[ 'zipcode' ]; ?>
          
          <a href="https://maps.google.com/maps?q=<?php echo( $addr ); ?>" target="_blank">
            <?php echo( $addr ); ?>
          </a>
        </td>
        <td><a href="tel:<?php echo( $cinfo[ 'phone' ] ); ?>"><?php echo( $cinfo[ 'phone' ] ); ?></a></td>
        <td><a href="view_shop_profile.php?wid=<?php echo( $cinfo[ 'wid' ] ); ?>"><?php echo( $cinfo[ 'wname' ] . " " . $cinfo[ 'wstreet_no' ] ); ?></a></td>
        <td><?php echo( $cinfo[ 'wage' ] ); ?></th>
        <td><?php $qs = "SELECT contact_action.aid
                         FROM contact_action
                         WHERE contact_action.cid = " . $cinfo[ 'id' ];
            
            $aqr = execute_query( $qs, $mc );
            
            $ainfo = mysql_fetch_array( $aqr );
            echo( $anames[ $ainfo[ 'aid' ] ] );
            
            while( $ainfo = mysql_fetch_array( $aqr ) ) {
              echo( ', ' . $anames[ $ainfo[ 'aid' ] ] );
            } ?>
        </td>
        <td><?php echo( $cinfo[ 'email' ] ); ?></td>
        <td><?php echo( $cinfo[ 'school' ] ); ?></td>
        <td><?php echo( $cinfo[ 'cdate' ] ); ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
