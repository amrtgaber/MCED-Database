<?php
/* File: load_navbar.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Loads the navbar.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}
?>

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="brand" href="home.php">Database</a>

      <div class="nav-collapse collapse">
        <ul class="nav pull-right">
          <li><p class="navbar-text pull-right">
            Logged in as <?php echo( $_SESSION[ 'username' ] ); ?>
          </p></li>
          <li class="divider-vertical"></li>
          <li><button type="button" id="logout" class="btn btn-small btn-inverse pull-right">Logout</button></li>
        </ul>

        <ul class="nav">
          <li><a href="view.php">View</a></li>

          <?php if( $_SESSION[ 'privilege_level' ] > 0 ) { ?>
            <li><a href="add.php">Add</a></li>
          <?php } ?>
          
          <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
            <li><a href="modify.php">Modify</a></li>
          <?php } ?>

          <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
            <li><a href="remove.php">Remove</a></li>
          <?php } ?>

          <?php if( $_SESSION[ 'privilege_level' ] > 3 ) { ?>
            <li><a href="manage_users.php">Manage Users</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
