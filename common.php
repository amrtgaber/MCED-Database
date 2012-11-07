<?php
/* File: common.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Common functions and data for kc99 database.
 */

/* Database credentials */
$MYSQL_HOST     = "localhost";
$MYSQL_USER     = "root";
$MYSQL_PASSWORD = "mceddb";
$DB_NAME        = "kc99_data";

/* Database error alert */
function database_error_alert( $mysql_error ) {
?>
<div class="alert alert-error">There was an error with the database. 
                               If you get this response more than once,
                               please try again later or contact jalhaj@mc-ed.org.
                               ERROR: <?php echo( $mysql_error ); ?>.</div>
<?php
}
?>
