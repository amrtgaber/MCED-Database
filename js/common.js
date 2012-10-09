$( document ).ready(function() {
  $( "#logout" ).click(function() {
    $.post(
      'logout.php', 
      function() { 
        window.location = "login.php";  
      }
    );
  });
});
