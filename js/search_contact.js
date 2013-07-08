$( document ).ready(function() {
  $( "#records" ).html( "Loading..." );
  $( "#records" ).load( "load_search_contact.php", function() {
    $( "#contact-table" ).dataTable({
      "iDisplayLength": 25,
      "sPaginationType": "full_numbers"
    });
  });
});
