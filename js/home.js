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

  $("#add").click(function() {
    window.location = "add.php";
  });

  $("#modify").click(function() {
    window.location = "modify.php";
  });

  $("#remove").click(function() {
    window.location = "remove.php";
  });

  $("#manage-users").click(function() {
    window.location = "manage_users.php";
  });
});
