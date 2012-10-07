<?php
/* File: generate_list.php
 * Author: Amr Gaber
 * Created: 9/29/2012
 * Description: Handles generate list page for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION['username'] ) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database View</title>

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/view_generate_list.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="home.php">Database</a>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
              <li><p class="navbar-text pull-right">
                Logged in as <?php echo($_SESSION['username']); ?>
              </p></li>
              <li class="divider-vertical"></li>
              <li><button type="button" id="logout" class="btn btn-small btn-inverse pull-right">Logout</button></li>
            </ul>
            <ul class="nav">
              <li><a href="view.php">View</a></li>
              <li><a href="add.php">Add</a></li>
              <li><a href="modify.php">Modify</a></li>
              <li><a href="remove.php">Remove</a></li>
              <li><a href="manage_users.php">Manage Users</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        
        <!-- Body -->
        <div class="span9">
          <div class="row-fluid">
            <div class="span12">
              <h2>What kind of list would you like to generate?</h2>
            </div>
            
            <div class="span11">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#basic-tab" data-toggle="tab">Basic List</a></li>
                <li><a href="#phone-bank-tab" data-toggle="tab">Phone Bank</a></li>
                <li><a href="#walk-tab" data-toggle="tab">Walk List</a></li>
              </ul>
              
              <div class="tab-content">
                <div class="tab-pane active" id="basic-tab">
                  <form id="basic-form">
                    <h4>Which people would you like to include in this list?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="workers">
                      Workers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="students">
                      Students
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="supporters">
                      Supporters
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="organizers">
                      Organizers
                    </label>

                    <br />

                    <h4>What information would you like to see?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="addresss">
                      Address
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="phone-number">
                      Phone Number
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="email">
                      Email
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="type">
                      Contact Type
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="assessment">
                      Assessment
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="assigned-organizer">
                      Assigned Organizer
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="workplace">
                      Workplace
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="wage">
                      Wage
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="school">
                      School
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="info" value="school-year">
                      Year in School
                    </label>

                    <br />

                    <button type="submit" class="btn btn-primary">Generate</button>
                    <button type="reset" class="btn">Clear</button>
                  </form>

                  <table id="basic-table" class="table table-striped table-hover table-condensed"></table>
                </div>
                
                <div class="tab-pane" id="phone-bank-tab">
                  <form id="phone-bank-form">
                    <h4>Which people would you like to include in this list?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="workers">
                      Workers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="students">
                      Students
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="supporters">
                      Supporters
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="organizers">
                      Organizers
                    </label>

                    <br />
                    
                    <button type="submit" class="btn btn-primary">Generate</button>
                    <button type="reset" class="btn">Clear</button>
                  </form>
                  
                  <table id="phone-bank-table" class="table table-striped table-hover table-condensed"></table>
                </div>

                <div class="tab-pane" id="walk-tab">
                  <form id="phone-bank-form">
                    <h4>Which people would you like to include in this list?</h4>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="workers">
                      Workers
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="students">
                      Students
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="supporters">
                      Supporters
                    </label>
                    
                    <label class="checkbox">
                      <input type="checkbox" name="people" value="organizers">
                      Organizers
                    </label>

                    <br />
                    
                    <button type="submit" class="btn btn-primary">Generate</button>
                    <button type="reset" class="btn">Clear</button>
                  </form>
                  
                  <table id="walk-table" class="table table-striped table-hover table-condensed"></table>
                </div>
              </div>
            </div>
          </div><!--/.row-fluid-->
        </div><!--/.span9-->
        
        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <img src="img/kc99-logo-9-27.png">
            <ul class="nav nav-list">
              <li class="nav-header">Common Tasks</li>
              <li class="active"><a href="#">Task</a></li>
              <li><a href="#">Task</a></li>
              <li><a href="#">Task</a></li>
              <li><a href="#">Task</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <hr>
      <footer class="footer">
        <p>Copyright &copy;&nbsp;2012 <a href="http://www.kansascity99.org/">KC99</a></p>
      </footer>
    </div><!--/.fluid-container-->
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/view_generate_list.js"></script>
  </body>

</html>

