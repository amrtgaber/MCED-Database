<?php
/* File: action_quick_search.php
 * Author: Amr Gaber
 * Created: 18/11/2012
 * Description: Returns the contact profile for KC99 database.
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

/* If id is present, populate form. */
if( !isset( $_GET[ 'id' ] ) ) {
  alert_error( "No contact selected." );
}
  
$id = mysql_real_escape_string( $_GET[ 'id' ] );

