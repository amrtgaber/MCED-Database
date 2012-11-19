$(document).ready(function() {
  $( "#view" ).click(function() {
    window.location = "view.php";
  });

  $( "#add" ).click(function() {
    window.location = "add.php";
  });

  $( "#modify" ).click(function() {
    window.location = "modify.php";
  });

  $( "#remove" ).click(function() {
    window.location = "remove.php";
  });

  $( "#manage-users" ).click(function() {
    window.location = "manage_users.php";
  });

  $( "#quick-search-button" ).click(function() {
    window.location = "search_contact.php?firstName=" + $( "#firstName" ).val() + "&lastName=" + $( "#lastName" ).val();
  });
});
