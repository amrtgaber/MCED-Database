$( document ).ready(function() {
  $( "#records" ).html( "Loading..." );
  $( "#records" ).load( "load_search_shop_profile.php", function() {
    $( "#shop-table" ).dataTable({
      "iDisplayLength": 25,
      "sPaginationType": "full_numbers"
    });
  });
});
