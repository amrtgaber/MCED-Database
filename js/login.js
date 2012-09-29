$(document).ready(function() {
  $("input").keydown(function() { $("#error").html(""); });

  $("form").submit(function() {
    $.post(
      'login_action.php', 
      $("form").serialize(),
      function() { 
        window.location = "home.php";  
      }
    ).fail(function() {
      $("#error").html("Incorrect username or password");
    });
    return false;
  });
});

