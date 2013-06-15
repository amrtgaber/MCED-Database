$( document ).ready(function() {
  $( "#search-results" ).html( "Loading..." );
  $( "#search-results" ).load( "load_search_contact.php?firstName=&lastName=", function() {
    $( "#contact-table" ).dataTable();
  });
  
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search-results" ).load( "load_search_contact.php?" + $( "#search" ).serialize(), function() {
      $( "#contact-table" ).dataTable();
    });
    return false;
  });
  
  /* Clear button */
  $( "#clear-button" ).click(function() {
    $( "#firstName" ).val( "" );
    $( "#lastName" ).val( "" );
    $( "#search-results" ).html( "" );
  });
});
