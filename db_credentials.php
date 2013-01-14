<?php
/* File: db_credentials.php
 * Author: Bryan Dorsey
 * Created: 2013/1/12
 * Description: Database credentials for kc99 database.
 */

/* connect to database */
function connect_to_database() {
  $mysql_connection = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
  mysql_select_db( "kc99_data" );
  return $mysql_connection;
}

?>
