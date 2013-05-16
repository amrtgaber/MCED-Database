<?php
/* File: load_footer.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Loads the footer.
 */

include( 'common.php' );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}
?>

<hr>
<div class="footer">
  <p>Copyright &copy;&nbsp;2012 <a href="http://kc99.org/">KC99</a></p>
</div>
