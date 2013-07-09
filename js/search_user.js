$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search-results" ).load( "load_search_user.php?" + $( "#search" ).serialize() );
    return false;
  });
  
  /* Clear button */
  $( "#clear-button" ).click(function() {
    $( "#username" ).val( "" );
    $( "#search-results" ).html( "" );
  });
});
