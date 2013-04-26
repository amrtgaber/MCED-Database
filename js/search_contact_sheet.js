$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search-results" ).load( "load_search_contact_sheet.php?" + $( "#search" ).serialize() );
    return false;
  });
  
  /* Clear button */
  $( "#clear-button" ).click(function() {
    $( "#firstName" ).val( "" );
    $( "#lastName" ).val( "" );
    $( "#search-results" ).html( "" );
  });
});
