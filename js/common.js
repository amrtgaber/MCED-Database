$( document ).ready(function() {
  $( "#navbar" ).load( "load_navbar.php", function() {
    $( "#logout" ).click(function() {
      $.post(
        'logout.php', 
        function() { 
          window.location = "login.php";  
        }
      );
    });
  });

  $( "#sidebar" ).load( "load_sidebar.php" );
  $( "#footer" ).load( "load_footer.php" );

});
