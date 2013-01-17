<?php
/* File: view_contact_sheet.php
 * Author: Amr Gaber
 * Created: 2013/1/16
 * Description: Shows contact sheet for KC99 database.
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

/* id must be present */
if( !isset( $_GET[ 'id' ] ) ) {
  alert_error( "No contact selected." );
}
  
$id = mysql_real_escape_string( $_GET[ 'id' ] );

/* get contact sheet information */
$qs = "SELECT contact_sheet.*,
              contacts.*,
              contact_phone.*
       FROM contact_sheet
       LEFT JOIN contacts      ON contact_sheet.cid = contacts.id
       LEFT JOIN contact_phone ON contact_sheet.cid = contact_phone.cid
       WHERE contact_sheet.id = " . $id;

$qr = execute_query( $qs, $mc );

$cs_info = mysql_fetch_array( $qr );

?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - View Contact Sheet</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/view_contact_sheet.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div id="navbar">
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- Body -->
        <div class="span9">
          <legend>View Contact Sheet</legend>
          
          <div class="row-fluid">
            <div class="span4" style="font-size: 2em;"><?php echo( $cs_info[ 'first_name' ] ); ?></div>
            <div class="span4" style="font-size: 2em;"><?php echo( $cs_info[ 'last_name' ] ); ?></div>
            
            <div class="span1 info-label">Workplace</div>
            <div class="span3 info-content"><?php echo( $cs_info[ 'workplace' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span1 info-label">Job</div>
            <div class="span2 info-content"><?php echo( $cs_info[ 'job' ] ); ?></div>
            
            <div class="span1 info-label">Shift</div>
            <div class="span2 info-content"><?php echo( $cs_info[ 'shift' ] ); ?></div>
            
            <div class="span1 info-label">Cell #</div>
            <div class="span2 info-content">
              <?php $cell = $cs_info[ "cell" ];
              
                if( $cell != 0 ) {
                  if( strlen( $cell ) == 10 ) {
                    /* Area code and phone number */
                    echo( "(" . substr( $cell, 0, 3 ) . ") "
                          . substr( $cell, 3, 3 ) . "-"
                          . substr( $cell, 6 ) );
                  } else {
                    /* Only phone number */
                    echo( substr( $cell, 0, 3 ) . "-"
                          . substr( $cell, 3 ) );
                  }
                }
              ?>
            </div>
            
            <div class="span1 info-label">Home #</div>
            <div class="span2 info-content">
              <?php $phone = $cs_info[ "phone" ];
              
                if( $phone != 0 ) {
                  if( strlen( $phone ) == 10 ) {
                    /* Area code and phone number */
                    echo( "(" . substr( $phone, 0, 3 ) . ") "
                          . substr( $phone, 3, 3 ) . "-"
                          . substr( $phone, 6 ) );
                  } else {
                    /* Only phone number */
                    echo( substr( $phone, 0, 3 ) . "-"
                          . substr( $phone, 3 ) );
                  }
                }
              ?>
            </div>
          </div>
          
          <div class="row-fluid">
            <div class="span1 info-label">Address</div>
            <div class="span5 info-content"><?php echo( $cs_info[ "street_no" ] ); ?></div>
            
            <div class="span1 info-label">City</div>
            <div class="span2 info-content"><?php echo( $cs_info[ "city" ] ); ?></div>
            
            <div class="span1 info-label">Zip</div>
            <div class="span2 info-content"><?php echo( $cs_info[ "zipcode" ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span2 info-label">Issues</div>
          </div>
          
          <div class="row-fluid info-content">
            <div class="span12 info-text"><?php echo( $cs_info[ 'issues' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span2 info-label">Reservations</div>
          </div>
          
          <div class="row-fluid info-content">
            <div class="span12 info-text"><?php echo( $cs_info[ 'reservations' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span2 info-label">Leaders Identified</div>
            <div class="span10 info-content"><?php echo( $cs_info[ 'leaders' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span2 info-label">Comments</div>
          </div>
          
          <div class="row-fluid info-content">
            <div class="span12 info-text"><?php echo( $cs_info[ 'comments' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span4 info-label">Assignments &amp; Follow Ups</div>
          </div>
          
          <div class="row-fluid info-content">
            <div class="span12 info-text"><?php echo( $cs_info[ 'assignments' ] ); ?></div>
          </div>

          <?php $rating = $cs_info[ 'rating' ]; ?>

          <div class="row-fluid">
            <div class="span1 info-label">Rating</div>
            <div class="span3 info-content">
              1 <i class="<?php if( $rating == 1 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i>&nbsp; &nbsp;
              2 <i class="<?php if( $rating == 2 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i>&nbsp; &nbsp;
              3 <i class="<?php if( $rating == 3 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i>&nbsp; &nbsp;
              4 <i class="<?php if( $rating == 4 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i>&nbsp; &nbsp;
              5 <i class="<?php if( $rating == 5 ) { echo( 'icon-star' ); } else { echo( 'icon-star-empty' ); } ?>"></i>
            </div>
            
            <div class="span2 info-label">
              Story
              <?php if( isset( $cs_info[ "story" ] ) ) { ?><i class="icon-ok"></i><?php } else { ?><i class="icon-remove"></i><?php } ?>
            </div>
            
            <div class="span2 info-label">
              Video
              <?php if( isset( $cs_info[ "video" ] ) ) { ?><i class="icon-ok"></i><?php } else { ?><i class="icon-remove"></i><?php } ?>
            </div>
            
            <div class="span2 info-label">
              Survey
              <?php if( isset( $cs_info[ "survey" ] ) ) { ?><i class="icon-ok"></i><?php } else { ?><i class="icon-remove"></i><?php } ?>
            </div>
            
            <div class="span2 info-label">
              <span title="Dues Authorization Card">DAC</span>
              <?php if( isset( $cs_info[ "dues_card" ] ) ) { ?><i class="icon-ok"></i><?php } else { ?><i class="icon-remove"></i><?php } ?>
            </div>
          </div>
          
          <div class="row-fluid">
            <div class="span4 info-label">
              Potential Legal Issues
              <?php if( isset( $cs_info[ "has_legal_issues" ] ) ) { ?><i class="icon-ok"></i><?php } else { ?><i class="icon-remove"></i><?php } ?>
            </div>
          </div>
          
          <div class="row-fluid info-content">
            <div class="span12 info-text"><?php echo( $cs_info[ 'legal_issues' ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span1 info-label">Organizer</div>
            <div class="span2 info-content">
              <?php
                $oid = $cs_info[ "cs_oid" ];
              
                $qs = "SELECT username
                       FROM users
                       WHERE id = " . $oid;
                
                $oqr = execute_query( $qs, $mc );
                
                $organizer_info = mysql_fetch_array( $oqr );
                
                echo( $organizer_info[ "username" ] );
              ?>
            </div>
            
            <div class="span2 info-label">Contact Type</div>
            <div class="span4 info-content"><?php echo( $cs_info[ "cs_contact_type" ] ); ?></div>
            
            <div class="span1 info-label">Date</div>
            <div class="span2 info-content"><?php echo( $cs_info[ "cs_date" ] ); ?></div>
          </div>
          
          <div class="row-fluid">
            <div class="span6 info-label">Notes</div>
            <div class="span6 info-label">Things to improve on</div>
          </div>
          
          <div class="row-fluid">
            <div class="span6 info-content info-text"><?php echo( $cs_info[ 'cs_notes' ] ); ?></div>
            <div class="span6 info-content info-text"><?php echo( $cs_info[ 'improvements' ] ); ?></div>
          </div>
        </div><!-- body -->
        
        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <div id="sidebar">
            </div>
          </div>
        </div>
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <div id="footer">
      </div>
    </div><!--/.fluid-container-->
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/view_contact_sheet.js"></script>
  </body>

</html>
