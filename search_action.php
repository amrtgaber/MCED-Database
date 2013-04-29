<?php
/* File: search_action.php
 * Author: Bryan Dorsey
 * Created: 3/3/2013
 * Description: Handles searching for actions and their corresponding contacts in the KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

include( "db_credentials.php" );
include( "common.php" );

/* Connect to database */
$mc = connect_to_database(); ?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Search for Action</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_contact.css" rel="stylesheet">
    <link href="css/load_contact_profile.css" rel="stylesheet">
      
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
        <legend>Search Action</legend>

          <form>
            <h4>Select an action</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span2">Action</div>
                <div class="span7">
                  <select name="actionsddl" id="actionsddl" class="span12">
                    <option value="">&lt; Select an Action &gt;</option>
                    <?php 
                      $qs = "SELECT actions.* FROM 
                             actions
                             ORDER BY actions.aname";
                      
                      $actions = execute_query($qs,$mc);
                      
                      while( $action_info = mysql_fetch_array( $actions ) ) {
                        if($_GET['actionsddl'] !=  htmlspecialchars($action_info[ "aid" ] ))
                        {
                          var_dump($_GET['actionsddl']);
                          var_dump($action_info[ "aid" ]);
                          echo( '<option value="' . htmlspecialchars($action_info[ "aid" ] ) . '">' . htmlspecialchars($action_info[ "aname" ] ) . '</option>' );
                        }else{
                          echo( '<option value="' . htmlspecialchars($action_info[ "aid" ] ) . '" selected >' . htmlspecialchars($action_info[ "aname" ] ) . '</option>' );
                        }
                      }
                      echo '</select>';
                    ?>
                  </select>
                </div>
                
                <div class="span3">
                  <a href="add_action.php" class="btn btn-primary span6 pull-right">Add Action</a>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span2">Contacts</div>
              <div class="span10" id="action-contacts">
                <?php
                  if ($_GET['actionsddl'] != "")
                  {
                
                    $qr = "SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.street_no, contacts.city, contacts.state, contacts.zipcode, contact_action.date
                         FROM contacts
                          JOIN contact_action on contacts.id = contact_action.cid
                         WHERE contact_action.aid = " . $_GET['actionsddl'] . " " . "
                         ORDER BY contacts.last_name";
                         
                    $contact_actions = execute_query($qr,$mc); ?>
                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th>Last Name</th>
                          <th>First Name</th>
                          <th>Address</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                  <?php if(mysql_num_rows($contact_actions) > 0)
                    {
                      while( $contact_action_info = mysql_fetch_array( $contact_actions ) ) { ?>
                        <tr>
                          <td><a href="search_contact.php?id=<?php echo( $contact_action_info[ "id" ] ); ?>"><?php echo( $contact_action_info[ "last_name" ] ); ?></a></td>
                          <td><a href="search_contact.php?id=<?php echo( $contact_action_info[ "id" ] ); ?>"><?php echo( $contact_action_info[ "first_name" ] ); ?></a></td>
                          <td><?php echo( $contact_action_info[ "street_no" ] . " " . $contact_action_info[ "city" ] . ", " . $contact_action_info[ "state" ] ); ?></td>
                          <td><?php echo( $contact_action_info[ "date" ] ); ?></td>
                        </tr>
                      <?php
                      }
                    }else{
                      echo('No contacts');
                    }
                    echo( "</tbody>");
                    echo( "</table>");
                  }                   
                ?>
              </div>
            </div>
          </form>
        </div>
        <!--/.span9-->
        
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
    <script src="js/search_actions.js"></script>
  </body>

</html>

