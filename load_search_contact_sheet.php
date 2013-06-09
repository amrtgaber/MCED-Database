<?php
/* File: load_search_contact_sheet.php
 * Author: Amr Gaber
 * Created: 2013/4/25
 * Description: Displays a table of contact with contact sheets.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}

/* First Name and Last Name */
$firstname = mysql_real_escape_string( strtolower( $_GET[ 'firstName' ] ) );
$lastname  = mysql_real_escape_string( strtolower( $_GET[ 'lastName' ] ) );

/* Connect to database */
$mc = connect_to_database();

/* Get contact info */
$qs = "SELECT contacts.*,
              contact_phone.*,
              workplaces.wid,
              workplaces.wname,
              workplaces.street_no AS wstreet_no
       FROM contacts
         LEFT JOIN contact_phone ON contacts.id = contact_phone.cid AND main = 1
         LEFT JOIN workers       ON contacts.id = workers.cid
         LEFT JOIN workplaces    ON workers.wid = workplaces.wid
       WHERE contacts.first_name LIKE '%" . $firstname . "%'
         AND contacts.last_name  LIKE '%" . $lastname . "%'
       GROUP BY contacts.id
       ORDER BY contacts.first_name, contacts.last_name";

$qr = execute_query( $qs, $mc );

if( mysql_num_rows( $qr ) > 0 ) { ?>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Employer</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
        while( $cinfo = mysql_fetch_array( $qr ) ) { ?>
            <tr>
              <td>
                  <a href="#<?php echo( $cinfo[ 'id' ] ); ?>" class="contact" data-toggle="collapse" data-parent="#search-results"><?php echo( $cinfo[ 'first_name' ] ); ?></a>
                  
                  <div id="<?php echo( $cinfo[ 'id' ] ); ?>" class="collapse">
                    <ul>
                      <?php $qs = "SELECT id,
                                          cs_date
                             FROM contact_sheet
                             WHERE contact_sheet.cid = " . $cinfo[ 'id' ] . "
                             ORDER BY cs_date DESC";
                             
                      $csqr = execute_query( $qs, $mc );
                    
                      if( mysql_num_rows( $csqr ) > 0 ) {
                        while( $cs_info = mysql_fetch_array( $csqr ) ) { ?>
                          <li><a href="view_contact_sheet.php?csid=<?php echo( $cs_info[ 'id' ] ); ?>"><?php echo( $cs_info[ "cs_date" ] ); ?></a></li>
                        <?php } ?>
                      <?php } ?>
                      
                      <li><a href="add_contact_sheet.php?csid=<?php echo( $cinfo[ 'id' ] ); ?>" class="btn btn-mini btn-info">+</a></li>
                    </ul>
                  </div>
              </td>
              <td><a href="#<?php echo( $cinfo[ 'id' ] ); ?>" class="contact accordion-toggle" data-toggle="collapse" data-parent="#search-results"><?php echo( $cinfo[ 'last_name' ] ); ?></a></td>
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
            </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
} else {
  alert_error( "No results found." );
}
