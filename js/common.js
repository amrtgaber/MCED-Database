$( document ).ready(function() {
  /* load navbar */
  $( "#navbar" ).load( "load_navbar.php", function() {
    $( "#logout-button" ).click(function() {
      $.post(
        'action_logout.php',
        function() { 
          window.location = "login.php";  
      });
    });
  });

  /* load sidebar */
  $( "#sidebar" ).load( "load_sidebar.php" );
  
  /* load footer */
  $( "#footer" ).load( "load_footer.php" );
});
