$( document ).ready(function() {
  $( "#records" ).html( "Loading..." );
  $( "#records" ).load( "load_search_user.php", function() {
    $( "#user-table" ).dataTable();
  });
});
