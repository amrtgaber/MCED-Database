$(document).ready(function() {
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
