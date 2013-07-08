$( document ).ready(function() {
  $( "#records" ).html( "Loading..." );
  $( "#records" ).load( "load_search_action.php", function() {
    $( "#action-table" ).dataTable({
      "iDisplayLength": 25,
      "sPaginationType": "full_numbers"
    });
  });
});
