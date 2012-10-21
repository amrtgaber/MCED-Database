<?php
/* File: generate_list.php
 * Author: Amr Gaber
 * Created: 9/29/2012
 * Description: Handles generate list page for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database View</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/generate_list.css" rel="stylesheet">
      
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
          <div class="row-fluid">
            <div class="span12">
              <h2>What kind of list would you like to generate?</h2>
            </div>
            
            <div class="span12">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#basic-tab" data-toggle="tab">Basic List</a></li>
                <li><a href="#phone-bank-tab" data-toggle="tab">Phone Bank</a></li>
                <li><a href="#walk-tab" data-toggle="tab">Walk List</a></li>
              </ul>
            </div> 
          </div>

          <div class="tab-content">
            <div class="tab-pane active" id="basic-tab">
              <form id="basic-form">
                <div class="row-fluid">
                  <div class="span12">
                    <h4>Which people would you like to include in this list?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="workers" value="true">
                      Workers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="students" value="true">
                      Students
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="supporters" value="true">
                      Supporters
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="organizers" value="true">
                      Organizers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="other" value="true">
                      Other
                    </label>
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                    <h4>What information would you like to see?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="contactType" value="true">
                      Contact Type
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="assessment" value="true">
                      Assessment
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="address" value="true">
                      Address
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="phoneNumber" value="true">
                      Phone Number
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="email" value="true">
                      Email
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="wage" value="true">
                      Wage
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="workplace" value="true">
                      Workplace
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="school" value="true">
                      School
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="schoolYear" value="true">
                      Expected year of graduation
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="assignedOrganizer" value="true">
                      Assigned Organizer
                    </label>
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                    <button type="submit" id="basic-submit" class="btn btn-primary btn-large">Generate</button>
                    <button type="reset" class="btn btn-large">Clear</button>
                  </div>
                </div>
              </form>
            </div>
                
            <div class="tab-pane" id="phone-bank-tab">
              <form id="phone-bank-form">
                <div class="row-fluid">
                  <div class="span12">
                    <h4>Which people would you like to include in this list?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="workers" value="true">
                      Workers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="students" value="true">
                      Students
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="supporters" value="true">
                      Supporters
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="organizers" value="true">
                      Organizers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="other" value="true">
                      Other
                    </label>
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                    <button type="submit" id="phone-bank-submit" class="btn btn-primary btn-large">Generate</button>
                    <button type="reset" class="btn btn-large">Clear</button>
                  </div>
                </div>
              </form>
            </div>

            <div class="tab-pane" id="walk-tab">
              <form>
                <div class="row-fluid">
                  <div class="span12">
                    <p>Coming soon...</p>
                  </div>
                </div>
              </form>
            </div>
          </div>    
        </div><!--/.span9-->
        
        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <div id="sidebar">
            </div>
          </div>
        </div>
      </div><!--/.row-fluid-->
      
      <div class="row-fluid">
        <div id="list" class="span12">
        </div>
      </div>

      <div id="modal-edit" class="modal hide fade">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Modify Contact</h3>
        </div>
          
        <form id="update">
          <div id="modalBodyEdit" class="modal-body">
          </div>
          
          <div class="modal-footer">
            <button type="submit" id="updateButton" class="btn btn-primary btn-large">Save Changes</button>
            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
        
      <div id="modal-remove" class="modal hide fade">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Are you sure?</h3>
        </div>
          
        <div id="modalBodyRemove" class="modal-body">
        </div>
        
        <div class="modal-footer">
          <button type="button" id="removeConfirm" class="btn btn-primary btn-danger">Remove</button>
          <button type="button" class="btn" data-dismiss="modal">Cancel</button>
        </div>
      </div>

      <!-- Footer -->
      <div id="footer">
      </div>
    </div><!--/.fluid-container-->
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/generate_list.js"></script>
  </body>

</html>

