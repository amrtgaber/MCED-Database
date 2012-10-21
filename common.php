<?php
/* File: common.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Common functions for kc99 database.
 */


/* Database error alert */
function database_error_alert( $mysql_error ) {
?>
<div class="alert alert-error">There was an error with the database. 
                               If you get this response more than once,
                               please try again later or contact admin@debrijja.com.
                               ERROR: <?php echo( $mysql_error ) ?>.</div>
<?php
}
?>