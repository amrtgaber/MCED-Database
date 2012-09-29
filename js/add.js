$(document).ready(function() {
  $("#logout").click(function() {
    $.post(
      'logout.php', 
      function() { 
        window.location = "login.php";  
      }
    );
  });

  $("#view").click(function() {
    window.location = "view.php";
  });
});
