<?php
/* File: common.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Common functions and data for kc99 database.
 */

/* connect to database */
function connect_to_database() {
  $mysql_connection = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
  mysql_select_db( "kc99_data" );
  return $mysql_connection;
}

/* execute_query */
function execute_query( $query_string, $mysql_connection ) {
  $query_resource = mysql_query( $query_string, $mysql_connection );

  if( mysql_errno() != 0 ) { ?>
    <div class="alert alert-error">There was an error with the database. 
       If you get this response more than once,
       please try again later or contact jalhaj@mc-ed.org.
       ERROR: <?php echo( $mysql_error ); ?>. <?php echo( $query_string ); ?></div>
  <?php
    exit;
  } else {
    return $query_resource;
  }
}

/* alert error */
function alert_error( $error_string ) { ?>
  <div class="alert alert-error">
    <?php echo( $error_string ); ?>
  </div>
  <?php exit;
}

?>
