$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search-results" ).load( "load_search_action.php?" + $( "#search" ).serialize() );
    return false;
  });
  
  /* Clear button */
  $( "#clear-button" ).click(function() {
    $( "#aname" ).val( "" );
    $( "#search-results" ).html( "" );
  });
});
