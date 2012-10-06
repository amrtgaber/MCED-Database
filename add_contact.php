<?php
/* File: add_contact.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles add contact page for KC99 database.
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
    <title>KC99 - Database Add</title>

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/add_contact.css" rel="stylesheet">
      
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
          <form>
            <legend>Add Contact</legend>
            <div class="well"> 
              <div class="row-fluid">
                <div class="span1">First Name</div>
                <div class="span5">
                  <input type="text" name="firstName" class="span12" placeholder="Type first name here" name="firstName">
                </div>
                
                <div class="span1">Last Name</div>
                <div class="span5">
                  <input type="text" name="lastName" class="span12" placeholder="Type last name here" name="lastName">
                </div>
              </div>

              <div class="row-fluid">
                <div class="span3">
                  <input type="checkbox" name="wageBelow10" value="true">
                  I make less than $10 an hour.
                </div>
                
                <div class="span1">Employer</div>
                <div class="span8">
                  <input type="text" name="employer" class="span12" placeholder="Type employer here" name="workplace">
                </div>
              </div>

              <div class="row-fluid">
                <div class="span1">Address</div>
                <div class="span8">
                  <input type="text" name="address" class="span12" placeholder="Type address here">
                </div>

                <div class="span1">Apt. no.</div>
                <div class="span2">
                  <input type="text" name="aptNo" class="span12" placeholder="Type Apt. no. here">
                </div>
              </div>
                
              <div class="row-fluid">
                <div class="span1">City</div>
                <div class="span6">
                  <input type="text" name="city" class="span12" placeholder="Type city here">
                </div>

                <div class="span1">State</div>
                <div class="span1">
                  <input type="text" name="state" class="span12" placeholder="State">
                </div>
                <div class="span1">Zipcode</div>
                <div class="span2">
                  <input type="text" name="zipcode" class="span12" placeholder="Type zipcode here">
                </div>
              </div>
                
              <div class="row-fluid">
                <div class="span1">Phone</div>
                <div class="span5">
                  <input type="text" name="phone" class="span12" placeholder="Type phone number here">
                </div>
                
                <div class="span1">Cell</div>
                <div class="span5">
                  <input type="text" name="cell" class="span12" placeholder="Type cell phone number here">
                </div>
              </div>
                
              <div class="row-fluid">
                <div class="span12">
                  <input type="checkbox" name="textUpdates" value="true">
                  Send me text message updates.
                </div>
              </div>

              <div class="row-fluid">
                <div class="span1">Email</div>
                <div class="span11">
                  <input type="text" name="email" class="span12" placeholder="Type email here">
                </div>
              </div>

              <div class="row-fluid">
                <div class="span12" id="error"></div>
              </div>
            </div>

            <div class="well">
              <div class="row-fluid">
                <div class="span4"><h5>Worker Information (Optional)</h5></div>
                <div class="span8"><h5>Student Information (Optional)</h5></div>
              </div>

              <div class="row-fluid">
                <div class="span1">Wage</div>
                <div class="span1 input-prepend">
                  <span class="add-on">$</span><input type="text" name="dollars" class="span12" placeholder="dollars">
                </div>
                <div class="span1 input-prepend">
                  <span class="add-on">.</span><input type="text" name="cents" class="span12" placeholder="cents">
                </div>
                
                <div class="span1"></div>

                <div class="span1">School</div>
                <div class="span4">
                  <input type="text" name="school" class="span12" placeholder="Type school here">
                </div>

                <div class="span1">Year</div>
                <div class="span1 input-prepend">
                  <span class="add-on">20</span><input type="text" name="year" class="span12" placeholder="year">
                </div>
              </div>
              
              <div class="row-fluid">
                <div class="span12" id="error-optional"></div>
              </div>
            </div>
              
            <div class="row-fluid">
              <div class="span3">
                <button type="submit" class="btn btn-primary btn-large">Add Contact</button>
                <button type="reset" class="btn btn-large">Clear</button>
              </div>
            </div>
          </form>
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
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/add_contact.js"></script>
  </body>

</html>

