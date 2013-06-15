$( document ).ready(function() {
  $( "#records" ).html( "Loading..." );
  $( "#records" ).load( "load_search_action.php", function() {
    $( "#action-table" ).dataTable();
  });
});
