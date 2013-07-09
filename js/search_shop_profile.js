$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search-results" ).load( "load_search_shop_profile.php?" + $( "#search" ).serialize() );
    return false;
  });
  
  /* Clear button */
  $( "#clear-button" ).click(function() {
    $( "#wname" ).val( "" );
    $( "#search-results" ).html( "" );
  });
});
